<script setup>
import { onMounted, onBeforeUnmount, ref, watch,defineProps } from 'vue'
import PrimaryButton from '../Components/PrimaryButton.vue'
import AppLayout from '../Layouts/AppLayout.vue'

const props = defineProps({
  LLM: Boolean // or whatever type it is
});

const cameraStatus=ref('');
const connectionStatus=ref('');
const predictionText = ref('');
const numOfFrames=ref('');
const predictionInnerText=ref('');
const predictionSentence = ref('');
const videoRef = ref(null);
const isStreaming = ref(false);
const sentencesArray=ref([]);
const llmResponseArray=ref([]);

let ws = null;
let interval = null;

watch(predictionInnerText, (newVal, oldVal) => {
  if (newVal && newVal !== oldVal) {
    predictionSentence.value += (predictionSentence.value ? ' ' : '') + newVal;
  }
})

onMounted(() => {
  const video = videoRef.value
  const WS_URL = "wss://sign-language-api-514415015721.me-central1.run.app/ws"
  ws = new WebSocket(WS_URL)


  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream
    })
    .catch(err => {
      cameraStatus.value="تم منع الوصول الى الكاميرا";
    })

  ws.onopen = () => {
    connectionStatus.value="متصل";
  }

  ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    
    if (data.status === "prediction") {
      predictionText.value = data.prediction;
      predictionInnerText.value=data.prediction;
      numOfFrames.value = `التقاط الصور: ${data.buffer_level}/30`
  }
}

  ws.onerror = (err) => {
    connectionStatus.value="تم قطع الاتصال اعد تحميل الصفحة";
  }

  ws.onclose = () => {
    connectionStatus.value="تم قطع الاتصال اعد تحميل الصفحة";
  }
})

onBeforeUnmount(() => {
  stopStreaming()
  if (ws) ws.close()
})


function startStreaming(){
   if (isStreaming.value) return // Already streaming

  const video = videoRef.value
  isStreaming.value = true

  interval = setInterval(() => {
    if (
      ws.readyState === WebSocket.OPEN &&
      video.readyState >= 2 &&
      video.videoWidth > 0 &&
      video.videoHeight > 0
    ) {
      const canvas = document.createElement('canvas')
      canvas.width = video.videoWidth
      canvas.height = video.videoHeight
      const ctx = canvas.getContext('2d')
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height)
      const dataUrl = canvas.toDataURL('image/jpeg')
      ws.send(dataUrl)
    }
  }, 70)
}

async function correctSentence(sentence,isLLM) {
  const apiKey = 'AIzaSyAymb7h68s42zt6xY7jmJMBUjqPiMyU27M'; // Replace with your Gemini API key
  const endpoint = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;
  let prompt='';
  if(isLLM){
    prompt= `اجب على السؤال بالعربية. \n${sentence}`;
  }
  else{
      prompt = `
      أنت مدقق لغوي محترف في اللغة العربية.

      مهمتك: تصحيح الجمل المدخلة فقط إذا كانت مفهومة وتحمل معنى صحيحًا.

      اتبع التعليمات التالية بدقة:
      1. إذا كان النص غير مفهوم أو لا يشكل جملة صحيحة، أعد النص كما هو بدون أي تعديل.
      2. إذا كان النص مفهومًا:
        - دمج الحروف أو الكلمات المفصولة إذا كانت تشكل كلمة صحيحة (مثال: "م ح م د" ← "محمد").
        - تصحيح الأخطاء النحوية البسيطة (مثل التذكير والتأنيث، ترتيب الكلمات، إلخ).
        - حافظ على نفس المعنى الأصلي دون تغييره.
        - لا تكتب أي شرح أو تعليق، فقط أعد النص المعدل مباشرة.

      النص:
      \n${sentence}
      `;
    }
  const res = await fetch(endpoint, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      contents: [
        {
          parts: [
            { text: prompt }
          ]
        }
      ]
    })
  });

  const data = await res.json();
  return data?.candidates?.[0]?.content?.parts?.[0]?.text ?? '';
}


async function stopStreaming() {
  isStreaming.value = false;
  if(predictionSentence.value){
    predictionSentence.value= await correctSentence(predictionSentence.value,false);
  sentencesArray.value.push(predictionSentence.value);
  }

  if (props.LLM === true) {
    const llmResponse = await correctSentence(predictionSentence.value, true);
    llmResponseArray.value.push(llmResponse);
  }
  predictionSentence.value='';

  if (interval) {
    clearInterval(interval);
    interval = null;
  }
}

function clearLastWord(){
  predictionSentence.value=predictionSentence.value.substring(0,predictionSentence.value.lastIndexOf(' '));
}

async function toVoice(sentence) {
  try {
    // Get the CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Call your Laravel route with fetch
    const response = await fetch('generate-speech', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
      },
      body: JSON.stringify({ text: sentence }),
    });

    if (!response.ok) {
      throw new Error('Failed to generate speech');
    }

    // Get the audio data as a blob
    const audioBlob = await response.blob();

    // Create URL and play audio
    const audioUrl = URL.createObjectURL(audioBlob);
    const audio = new Audio(audioUrl);
    audio.play();
  } catch (error) {
    console.error('Speech error:', error);
  }
}



</script>


<template >
    <AppLayout/>
    
  <div class="flex justify-center items-start gap-6 p-7 flex-wrap md:flex-nowrap  min-h-screen ">
    <!-- 🎥 Left: Live Video -->
    <div>
      <div class="border-1 border-[#64CCC5] rounded-xl shadow-lg overflow-hidden" style="width: 800px; position: relative;">
        <video
        id="video"
        ref="videoRef"
        autoplay
        playsinline
        class="w-full h-[500px] object-cover"
        ></video>
        <!-- Flex container to place paragraphs side by side -->\
        <div class="absolute bottom-0 w-full bg-opacity-70 bg-[#176B87] text-gray-100 text-sm p-2 flex justify-between items-center">
          <p class="mx-2"> <strong>التوقع:</strong> {{ predictionText }}</p>
          <p class="mx-2"><strong>الاطارات: </strong>{{ numOfFrames }}</p>
        </div>
      </div>

      <div class="mt-3 flex justify-center items-center ">
          <div v-if="connectionStatus==='متصل' && !cameraStatus" >
            <p  class="mx-auto w-fit px-4 py-1 bg-green-700 text-white text-sm rounded shadow"><strong> {{ connectionStatus }}</strong></p>
          </div>
          <div v-else>
            <p v-if="cameraStatus" class="mx-auto w-fit px-4 py-1 bg-red-700 text-white text-sm rounded shadow"><strong> {{ cameraStatus }}</strong></p>
            <p v-if="connectionStatus!=='متصل' && !cameraStatus" class="mx-auto w-fit px-4 py-1 bg-red-700 text-white text-sm rounded shadow"><strong> {{ connectionStatus }}</strong></p>
          </div>
      </div>
    </div>

    <!-- 💬 Right: Live Chat & Controls -->
    <div class="flex flex-col" style="width: 350px;">
      <!-- Live Chat Box -->
      <div class="flex flex-col bg-[#EEEEEE] rounded-xl border-1 border-[#64CCC5] shadow-md h-[450px] overflow-hidden">
        <div class="bg-[#176B87] text-center px-4 py-2 font-semibold text-[#f1f1f1] border-b">
          الترجمة
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-4 text-sm" dir="rtl">
          <div
            v-for="(sentence, index) in sentencesArray"
            :key="index"
            class="space-y-2"
          >
          <!-- User message + button (in the same row) -->
          <div class="flex justify-start items-center space-x-reverse space-x-2">
            <!-- User message -->
            <p class="bg-blue-500 text-white px-4 py-2 rounded-xl max-w-[80%] shadow text-right">
              {{ sentence }}
            </p>
            <!-- Button -->
            <button
              @click="toVoice(sentence)"
              class="p-2 text-gray-700 hover:text-blue-500"
              title="Read out loud"
            >
              <svg class="w-6 h-6" fill="none" stroke-width="1.5" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
              </svg>
            </button>
          </div>
        <!-- Bot Response (Left Aligned, below the user sentence) -->
        <div v-if="llmResponseArray[index]" class="flex justify-end">
          <p class="bg-gray-200 text-black px-4 py-2 rounded-xl max-w-[80%] shadow text-right">
            {{ llmResponseArray[index] }}
          </p>
        </div>
      </div>
</div>


      </div>

      <!-- Control Buttons -->
      <div class="bg-[#176B87] rounded-xl border-1 border-[#64CCC5] p-4 flex justify-center space-x-4 mt-2">
        <PrimaryButton @click="clearLastWord()">حذف آخر كلمة</PrimaryButton>
        <PrimaryButton @click="stopStreaming()">إنهاء</PrimaryButton>
        <PrimaryButton @click="startStreaming()" class=" focus:bg-[#64CCC5] focus:border-2 focus:border-[#053B50]">ابدأ</PrimaryButton>
      </div>
    </div>
  </div>
</template>




