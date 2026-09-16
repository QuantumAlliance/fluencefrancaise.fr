<template>
  <div class="bg-white rounded-2xl border-2 border-[#0055A4]/25 shadow-sm px-4 py-3 flex items-center gap-3">
    <!-- Play / Pause -->
    <button
      @click="togglePlay"
      class="flex-shrink-0 w-11 h-11 rounded-full bg-[#0055A4] hover:bg-[#003d7a] text-white flex items-center justify-center shadow-md shadow-[#0055A4]/30 transition-colors"
      :aria-label="isPlaying ? 'Pause' : 'Play'"
    >
      <svg v-if="!isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M8 5v14l11-7z" />
      </svg>
      <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M6 4h4v16H6zM14 4h4v16h-4z" />
      </svg>
    </button>

    <!-- Progress + Time -->
    <div class="flex-1 flex flex-col gap-1 min-w-0">
      <input
        type="range"
        min="0"
        :max="duration || 0"
        step="0.1"
        :value="currentTime"
        @input="seek($event)"
        class="audio-range w-full h-1.5 rounded-full appearance-none cursor-pointer"
        :style="rangeStyle"
      />
      <div class="flex items-center justify-between text-[11px] text-gray-500 font-mono tabular-nums">
        <span>{{ formatTime(currentTime) }}</span>
        <span>{{ formatTime(duration) }}</span>
      </div>
    </div>

    <!-- Speed -->
    <button
      @click="cycleSpeed"
      class="flex-shrink-0 text-[11px] font-bold text-[#003d7a] bg-[#0055A4]/10 hover:bg-[#0055A4]/20 border border-[#0055A4]/30 px-2.5 py-1 rounded-md transition-colors min-w-[3rem] text-center"
      title="Playback speed"
    >
      {{ speed }}x
    </button>

    <!-- Volume / Mute -->
    <button
      @click="toggleMute"
      class="flex-shrink-0 text-gray-500 hover:text-[#003d7a] p-1 rounded transition-colors"
      :aria-label="muted ? 'Unmute' : 'Mute'"
    >
      <svg v-if="!muted" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
      </svg>
      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l4-4m0 4l-4-4"/>
      </svg>
    </button>

    <audio
      ref="audioEl"
      :src="resolvedSrc"
      preload="none"
      controlsList="nodownload"
      @contextmenu.prevent
      @timeupdate="onTimeUpdate"
      @loadedmetadata="onLoaded"
      @ended="onEnded"
      @play="isPlaying = true"
      @pause="isPlaying = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'

const props = defineProps({
  src: { type: String, required: true },
})

const audioEl = ref(null)
const isPlaying = ref(false)
const currentTime = ref(0)
const duration = ref(0)
const muted = ref(false)
const speed = ref(1)
const SPEEDS = [0.75, 1, 1.25, 1.5, 2]

// Use a blob URL so download managers (IDM, etc.) can't intercept the .mp3 request.
// IMPORTANT: blob is fetched LAZILY on first user click — never on mount. With many
// AudioPlayer instances on a page (e.g. 200 on Exam Prep preview), eager loading
// would fire hundreds of full-file downloads instantly and trip the rate limiter.
const blobSrc = ref('')
const blobLoaded = ref(false)
const loadingBlob = ref(false)

const resolvedSrc = computed(() => blobSrc.value || '')

const loadAsBlob = async () => {
  if (loadingBlob.value || blobLoaded.value) return
  if (!props.src) return
  loadingBlob.value = true
  try {
    const res = await fetch(props.src, { credentials: 'same-origin' })
    if (!res.ok) {
      blobSrc.value = props.src // fallback to raw URL
    } else {
      const blob = await res.blob()
      blobSrc.value = URL.createObjectURL(blob)
    }
    blobLoaded.value = true
  } catch (e) {
    blobSrc.value = props.src
    blobLoaded.value = true
  } finally {
    loadingBlob.value = false
  }
}

const resetBlob = () => {
  if (blobSrc.value && blobSrc.value.startsWith('blob:')) {
    URL.revokeObjectURL(blobSrc.value)
  }
  blobSrc.value = ''
  blobLoaded.value = false
}

const togglePlay = async () => {
  if (!audioEl.value) return
  // Lazy: only fetch the file the first time the user clicks Play.
  if (!blobLoaded.value) {
    await loadAsBlob()
    await nextTick() // let the <audio :src> bind the new blob URL
  }
  if (audioEl.value.paused) {
    try { await audioEl.value.play() } catch (e) {}
  } else {
    audioEl.value.pause()
  }
}

const seek = (e) => {
  if (!audioEl.value) return
  const t = parseFloat(e.target.value)
  audioEl.value.currentTime = t
  currentTime.value = t
}

const onTimeUpdate = () => {
  if (audioEl.value) currentTime.value = audioEl.value.currentTime
}

const onLoaded = () => {
  if (audioEl.value) duration.value = audioEl.value.duration || 0
}

const onEnded = () => {
  isPlaying.value = false
  currentTime.value = 0
}

const toggleMute = () => {
  if (!audioEl.value) return
  audioEl.value.muted = !audioEl.value.muted
  muted.value = audioEl.value.muted
}

const cycleSpeed = () => {
  const idx = SPEEDS.indexOf(speed.value)
  const next = SPEEDS[(idx + 1) % SPEEDS.length]
  speed.value = next
  if (audioEl.value) audioEl.value.playbackRate = next
}

const formatTime = (s) => {
  if (!isFinite(s) || s < 0) return '0:00'
  const m = Math.floor(s / 60)
  const sec = Math.floor(s % 60)
  return `${m}:${sec.toString().padStart(2, '0')}`
}

const rangeStyle = computed(() => {
  const pct = duration.value > 0 ? (currentTime.value / duration.value) * 100 : 0
  return {
    background: `linear-gradient(to right, #0055A4 0%, #0055A4 ${pct}%, #e5e7eb ${pct}%, #e5e7eb 100%)`,
  }
})

watch(() => props.src, () => {
  // src changed (or initial mount) — reset state, but DO NOT fetch.
  // Fetch happens only on first user click (togglePlay).
  isPlaying.value = false
  currentTime.value = 0
  duration.value = 0
  resetBlob()
}, { immediate: true })

onBeforeUnmount(() => {
  resetBlob()
})
</script>

<style scoped>
.audio-range::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #0055A4;
  border: 2px solid white;
  box-shadow: 0 1px 3px rgba(0, 85, 164, 0.4);
  cursor: pointer;
  transition: transform 0.15s;
}

.audio-range::-webkit-slider-thumb:hover {
  transform: scale(1.2);
}

.audio-range::-moz-range-thumb {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #0055A4;
  border: 2px solid white;
  box-shadow: 0 1px 3px rgba(0, 85, 164, 0.4);
  cursor: pointer;
}
</style>
