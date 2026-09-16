import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// Module-level interval reference — survives component unmounts
let _intervalId = null

export const useTimerStore = defineStore('timer', () => {
    const isTimerRunning = ref(false)
    const sessionTimerSeconds = ref(0)
    const sessionTimerStopped = ref(false)
    const sessionTimerStartedAt = ref(null) // absolute timestamp (ms)
    const activeGroupName = ref(null)       // shown in sticky widget
    const timerOwner = ref(null)            // 'group' | 'student-record' | null
    const timerStudentId = ref(null)        // student whose record timer is running
    const timerStudentName = ref(null)      // student name (for sticky widget)
    const timerGroupId = ref(null)          // group ID whose session timer is running
    const pendingOpenStudentModal = ref(false) // set by "Go to Session" to reopen the modal

    const setTimerRunning = (running) => {
        isTimerRunning.value = running
    }

    const startTimer = (accumulatedSeconds = 0, owner = 'group') => {
        if (_intervalId) clearInterval(_intervalId)
        sessionTimerStartedAt.value = Date.now() - accumulatedSeconds * 1000
        isTimerRunning.value = true
        timerOwner.value = owner
        _intervalId = setInterval(() => {
            sessionTimerSeconds.value = Math.floor((Date.now() - sessionTimerStartedAt.value) / 1000)
        }, 500)
    }

    const stopTimer = () => {
        if (_intervalId) {
            clearInterval(_intervalId)
            _intervalId = null
        }
        if (sessionTimerStartedAt.value !== null) {
            sessionTimerSeconds.value = Math.floor((Date.now() - sessionTimerStartedAt.value) / 1000)
        }
        isTimerRunning.value = false
        timerOwner.value = null
        sessionTimerStopped.value = true
    }

    const resetTimer = () => {
        if (_intervalId) {
            clearInterval(_intervalId)
            _intervalId = null
        }
        isTimerRunning.value = false
        timerOwner.value = null
        sessionTimerSeconds.value = 0
        sessionTimerStopped.value = false
        sessionTimerStartedAt.value = null
        activeGroupName.value = null
        timerStudentId.value = null
        timerStudentName.value = null
        timerGroupId.value = null
        pendingOpenStudentModal.value = false
    }

    const formattedTimer = computed(() => {
        const h = Math.floor(sessionTimerSeconds.value / 3600)
        const m = Math.floor((sessionTimerSeconds.value % 3600) / 60)
        const s = sessionTimerSeconds.value % 60
        const parts = []
        if (h > 0) parts.push(`${h}h`)
        if (m > 0) parts.push(`${m}m`)
        parts.push(`${s}s`)
        return parts.join(' ')
    })

    return {
        isTimerRunning,
        sessionTimerSeconds,
        sessionTimerStopped,
        sessionTimerStartedAt,
        activeGroupName,
        timerOwner,
        timerStudentId,
        timerStudentName,
        timerGroupId,
        pendingOpenStudentModal,
        setTimerRunning,
        startTimer,
        stopTimer,
        resetTimer,
        formattedTimer,
    }
})
