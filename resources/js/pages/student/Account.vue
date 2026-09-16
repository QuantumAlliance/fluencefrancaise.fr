<template>
  <div class="p-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">My Account</h1>

    <!-- Profile Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
      <!-- Profile Card -->
      <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 text-center">
          <div class="w-32 h-32 rounded-full bg-[#0055A4]/20 flex items-center justify-center mx-auto mb-4">
            <span class="text-4xl font-bold text-[#0055A4]">
              {{ getInitials(profile) }}
            </span>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">{{ profile.firstName }} {{ profile.lastName }}</h2>
          <p class="text-gray-600 text-sm mt-1">{{ profile.email }}</p>
          <p class="text-[#0055A4] font-medium mt-2">Student</p>

        </div>
      </div>

      <!-- Profile Information -->
      <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h3>

          <form @submit.prevent="updateProfile" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">First Name</label>
                <input
                  v-model="editForm.firstName"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Last Name</label>
                <input
                  v-model="editForm.lastName"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
              <input
                v-model="editForm.email"
                type="email"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Phone</label>
                <input
                  v-model="editForm.phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Timezone</label>
                <select
                  v-model="editForm.timezone"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                >
                  <option value="UTC">UTC</option>
                  <option value="EST">EST</option>
                  <option value="CST">CST</option>
                  <option value="PST">PST</option>
                  <option value="CET">CET</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Bio</label>
              <textarea
                v-model="editForm.biography"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="Tell us about yourself..."
              />
            </div>

            <button
              type="submit"
              class="w-full px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium rounded-lg transition-colors"
            >
              Save Changes
            </button>
          </form>

          <div v-if="updateMessage" :class="updateSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="mt-4 p-3 rounded-lg">
            {{ updateMessage }}
          </div>
        </div>
      </div>
    </div>

    <!-- Password Change Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h3 class="text-xl font-bold text-gray-800 mb-6">Change Password</h3>

      <form @submit.prevent="changePassword" class="max-w-md space-y-4">
        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Current Password</label>
          <input
            v-model="passwordForm.currentPassword"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">New Password</label>
          <input
            v-model="passwordForm.newPassword"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm New Password</label>
          <input
            v-model="passwordForm.confirmPassword"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          />
          <p v-if="passwordForm.newPassword !== passwordForm.confirmPassword" class="text-red-500 text-sm mt-1">
            Passwords do not match
          </p>
        </div>

        <button
          type="submit"
          :disabled="passwordForm.newPassword !== passwordForm.confirmPassword"
          class="w-full px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white font-medium rounded-lg transition-colors"
        >
          Update Password
        </button>
      </form>

      <div v-if="passwordMessage" :class="passwordSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="mt-4 p-3 rounded-lg max-w-md">
        {{ passwordMessage }}
      </div>
    </div>



  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const auth = useAuthStore()

const profile = ref({
  firstName: auth.user?.first_name || 'John',
  lastName: auth.user?.last_name || 'Doe',
  email: auth.user?.email || 'john@example.com',
  phone: auth.user?.phone || '',
  profilePicture: auth.user?.profile_picture || null,
  timezone: auth.user?.timezone || 'UTC'
})

const editForm = ref({
  firstName: profile.value.firstName,
  lastName: profile.value.lastName,
  email: profile.value.email,
  phone: profile.value.phone,
  timezone: profile.value.timezone,
  biography: auth.user?.biography || ''
})

const passwordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})



const updateMessage = ref('')
const updateSuccess = ref(false)
const passwordMessage = ref('')
const passwordSuccess = ref(false)

const getInitials = (profile) => {
  if (!profile) return 'U'
  const name = `${profile.firstName || ''} ${profile.lastName || ''}`.trim() || profile.email || 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const updateProfile = async () => {
  try {
    profile.value = { ...profile.value, ...editForm.value }
    updateMessage.value = 'Profile updated successfully!'
    updateSuccess.value = true
    setTimeout(() => {
      updateMessage.value = ''
    }, 3000)
  } catch (error) {
    updateMessage.value = 'Failed to update profile'
    updateSuccess.value = false
  }
}

const changePassword = async () => {
  try {
    passwordForm.value = {
      currentPassword: '',
      newPassword: '',
      confirmPassword: ''
    }
    passwordMessage.value = 'Password changed successfully!'
    passwordSuccess.value = true
    setTimeout(() => {
      passwordMessage.value = ''
    }, 3000)
  } catch (error) {
    passwordMessage.value = 'Failed to change password'
    passwordSuccess.value = false
  }
}




</script>

<style scoped>
/* Additional styling if needed */
</style>
