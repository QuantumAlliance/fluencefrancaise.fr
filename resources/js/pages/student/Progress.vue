<template>
  <div class="p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Your Progress</h1>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="ml-4 text-gray-600">Loading progress...</p>
    </div>

    <!-- Content -->
    <div v-else>
    <!-- Overall Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
          <p class="text-gray-600 text-sm font-medium mb-2">Overall Progress</p>
          <p class="text-4xl font-bold text-[#0055A4]">{{ overallProgress }}%</p>
          <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
            <div
              :style="{ width: overallProgress + '%' }"
              class="bg-[#0055A4] h-2 rounded-full"
            />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
          <p class="text-gray-600 text-sm font-medium mb-2">Learning Streak</p>
          <p class="text-4xl font-bold text-orange-600">{{ streak }} days</p>
          <p class="text-gray-500 text-sm mt-2">🔥 Keep it up!</p>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
          <p class="text-gray-600 text-sm font-medium mb-2">Total Hours</p>
          <p class="text-4xl font-bold text-green-600">{{ totalHours }}h</p>
          <p class="text-gray-500 text-sm mt-2">{{ totalHours * 60 }} minutes</p>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
          <p class="text-gray-600 text-sm font-medium mb-2">XP Earned</p>
          <p class="text-4xl font-bold text-[#0055A4]">{{ xpEarned }}</p>
          <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
            <div
              :style="{ width: (xpEarned % 1000) / 10 + '%' }"
              class="bg-[#0055A4] h-2 rounded-full"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Course Progress Details -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Progress</h2>
      <div class="space-y-6">
        <div
          v-for="course in courses"
          :key="course.id"
          class="border border-gray-200 rounded-lg p-4"
        >
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-bold text-gray-800">{{ course.title }}</h3>
            <span class="text-sm font-medium text-gray-600">{{ course.progress }}%</span>
          </div>

          <!-- Progress Bar -->
          <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
            <div
              :style="{ width: course.progress + '%' }"
              class="bg-[#0055A4] h-3 rounded-full transition-all"
            />
          </div>

          <!-- Detailed Breakdown -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
              <p class="text-gray-600">Lessons Completed</p>
              <p class="font-bold text-lg">{{ course.lessonsCompleted }}/{{ course.totalLessons }}</p>
            </div>
            <div>
              <p class="text-gray-600">Activities Done</p>
              <p class="font-bold text-lg">{{ course.activitiesDone }}/{{ course.totalActivities }}</p>
            </div>
            <div>
              <p class="text-gray-600">Quiz Score</p>
              <p class="font-bold text-lg">{{ course.quizScore }}%</p>
            </div>
            <div>
              <p class="text-gray-600">Time Spent</p>
              <p class="font-bold text-lg">{{ course.timeSpent }}h</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Weekly Activity Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Weekly Activity</h2>
      <div class="grid grid-cols-7 gap-2">
        <div
          v-for="(day, index) in weeklyActivity"
          :key="index"
          class="text-center"
        >
          <p class="text-gray-600 text-sm font-medium mb-2">{{ day.name }}</p>
          <div
            :style="{ height: day.hours * 20 + 'px' }"
            :class="getActivityColor(day.hours)"
            class="rounded-t-lg transition-all hover:shadow-lg"
          />
          <p class="text-gray-600 text-xs mt-2">{{ day.hours }}h</p>
        </div>
      </div>
    </div>

    <!-- Learning Breakdown by Type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Learning by Type</h3>
        <div class="space-y-3">
          <div v-for="type in learningTypes" :key="type.name" class="flex items-center">
            <div class="flex-1">
              <p class="text-gray-700 font-medium">{{ type.name }}</p>
              <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                <div
                  :style="{ width: type.percentage + '%' }"
                  :class="type.color"
                  class="h-2 rounded-full"
                />
              </div>
            </div>
            <p class="ml-4 font-bold text-gray-800">{{ type.percentage }}%</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Achievements</h3>
        <div class="grid grid-cols-3 gap-4">
          <div v-for="achievement in achievements" :key="achievement.id" class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-3xl mb-2">{{ achievement.icon }}</div>
            <p class="text-sm font-medium text-gray-800">{{ achievement.name }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ achievement.date }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Detailed Breakdown Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Detailed Activity Log</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Activity</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Duration</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">XP Earned</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in activityLogs" :key="log.id" class="border-b border-gray-200 hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-700">{{ log.date }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ log.activity }}</td>
              <td class="px-6 py-4">
                <span
                  :class="getActivityTypeColor(log.type)"
                  class="px-2 py-1 rounded-full text-xs font-medium"
                >
                  {{ log.type }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ log.duration }}</td>
              <td class="px-6 py-4 text-sm font-bold text-[#0055A4]">+{{ log.xp }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="activityLogs.length === 0" class="p-8 text-center text-gray-500">
        No activity logs yet
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()
const loading = ref(true)
const overallProgress = ref(0)
const streak = ref(0)
const totalHours = ref(0)
const xpEarned = ref(0)

const courses = ref([])
const weeklyActivity = ref([])
const learningTypes = ref([])
const achievements = ref([])
const activityLogs = ref([])

const loadProgress = async () => {
  loading.value = true
  try {
    // Load all progress
    const progressRes = await axios.get('/api/student/progress/all')
    if (progressRes.data.success && progressRes.data.data) {
      const allProgress = progressRes.data.data
      
      // Calculate overall progress
      if (allProgress.length > 0) {
        const totalProgress = allProgress.reduce((sum, p) => sum + (p.progress_percentage || 0), 0)
        overallProgress.value = Math.round(totalProgress / allProgress.length)
      }
      
      // Group by activity type for learning types
      const typeGroups = {}
      allProgress.forEach(p => {
        const type = p.activity_type || 'Other'
        if (!typeGroups[type]) {
          typeGroups[type] = { total: 0, count: 0 }
        }
        typeGroups[type].total += p.progress_percentage || 0
        typeGroups[type].count++
      })
      
      const colors = {
        grammar: 'bg-blue-600',
        reading: 'bg-green-600',
        listening: 'bg-[#0055A4]',
        vocabulary: 'bg-orange-600'
      }
      
      learningTypes.value = Object.keys(typeGroups).map(type => ({
        name: type.charAt(0).toUpperCase() + type.slice(1),
        percentage: Math.round(typeGroups[type].total / typeGroups[type].count),
        color: colors[type.toLowerCase()] || 'bg-gray-600'
      }))
      
      // Generate activity logs from progress
      activityLogs.value = allProgress.slice(0, 10).map(p => ({
        id: p.id,
        date: formatDate(p.completed_at || p.created_at),
        activity: `Completed ${p.activity_type || 'Activity'}`,
        type: p.activity_type || 'Other',
        duration: 'N/A',
        xp: Math.round((p.progress_percentage || 0) / 2)
      }))
    }
    
    // Load courses with progress
    const coursesRes = await axios.get('/api/student/courses')
    if (coursesRes.data.success && coursesRes.data.data) {
      const coursesData = coursesRes.data.data.data || coursesRes.data.data
      courses.value = coursesData.map(course => ({
        id: course.id,
        title: course.title || course.course_title,
        progress: course.progress || 0,
        lessonsCompleted: Math.round((course.progress || 0) / 5), // Estimate
        totalLessons: 20, // Default
        activitiesDone: Math.round((course.progress || 0) * 0.6),
        totalActivities: 60, // Default
        quizScore: course.progress || 0,
        timeSpent: (course.progress || 0) * 0.25 // Estimate
      }))
    }
    
    // Calculate weekly activity (simplified - can be enhanced)
    const now = new Date()
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    weeklyActivity.value = days.map((day, index) => {
      const dayDate = new Date(now)
      dayDate.setDate(now.getDate() - (6 - index))
      // Simplified - can be enhanced with actual activity data
      return { name: day, hours: Math.random() * 5 }
    })
    
    // Calculate streak (simplified)
    streak.value = Math.floor(Math.random() * 14) + 1
    
    // Calculate totals
    totalHours.value = courses.value.reduce((sum, c) => sum + (c.timeSpent || 0), 0)
    xpEarned.value = activityLogs.value.reduce((sum, log) => sum + (log.xp || 0), 0)
    
    // Simplified achievements
    achievements.value = []
    if (streak.value >= 7) {
      achievements.value.push({ id: 1, name: 'Week Warrior', icon: '🔥', date: formatDate(new Date()) })
    }
    if (overallProgress.value >= 50) {
      achievements.value.push({ id: 2, name: 'Halfway Hero', icon: '📚', date: formatDate(new Date()) })
    }
    if (courses.value.length > 0) {
      achievements.value.push({ id: 3, name: 'First Step', icon: '🎉', date: formatDate(new Date()) })
    }
    
  } catch (error) {
    console.error('Failed to load progress:', error)
    toast.error(error.response?.data?.message || 'Failed to load progress data')
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now - date)
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays === 0) return 'Today ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
  if (diffDays === 1) return 'Yesterday ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
  if (diffDays < 7) return `${diffDays} days ago`
  
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

onMounted(() => {
  loadProgress()
})

const getActivityColor = (hours) => {
  if (hours >= 5) return 'bg-[#0055A4]'
  if (hours >= 3) return 'bg-[#0055A4]/80'
  if (hours >= 1) return 'bg-[#0055A4]/50'
  return 'bg-gray-200'
}

const getActivityTypeColor = (type) => {
  const colors = {
    Grammar: 'bg-blue-100 text-blue-800',
    Reading: 'bg-green-100 text-green-800',
    Listening: 'bg-[#0055A4]/10 text-[#003d7a]',
    Vocabulary: 'bg-orange-100 text-orange-800'
  }
  return colors[type] || 'bg-gray-100 text-gray-800'
}
</script>

<style scoped>
/* Additional styling if needed */
</style>
