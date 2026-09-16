<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showAddForm = true"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add Tutor
      </button>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 flex gap-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search tutors..."
        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
      />
      <select
        v-model="filterStatus"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
      >
        <option value="">All Status</option>
        <option value="full_time">Full Time</option>
        <option value="part_time">Part Time</option>
        <option value="stopped">No Classes</option>
      </select>
    </div>

    <!-- Tutors Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading tutors...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tutor Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tutor in filteredTutors" :key="tutor.id" :class="[
            'border-b border-gray-200 transition-all duration-300',
            tutor.working_status === 'stopped' ? 'bg-red-50/50 hover:bg-red-50' : 
            tutor.working_status === 'part_time' ? 'bg-green-50/50 hover:bg-green-50' :
            tutor.working_status === 'full_time' ? 'bg-purple-50/50 hover:bg-purple-50' : 'hover:bg-gray-50'
          ]">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3 cursor-pointer group" @click="openStats(tutor)">
                <div class="w-8 h-8 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#0055A4]/30 transition-colors">
                  <span class="text-xs font-bold text-[#0055A4]">
                    {{ getInitials(tutor) }}
                  </span>
                </div>
                <span class="font-medium text-gray-800 group-hover:text-[#0055A4] transition-colors">{{ tutor.name }}</span>
                <span
                  v-if="tutor.pending_timer_edits > 0"
                  class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse"
                  :title="`${tutor.pending_timer_edits} pending timer edit request(s)`"
                >
                  {{ tutor.pending_timer_edits }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700">{{ tutor.email }}</td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ tutor.joinedDate }}</td>
            <td class="px-6 py-4">
              <!-- Custom Status Dropdown -->
              <div class="relative" @click.stop>
                <button
                  @click="statusDropdownId = statusDropdownId === tutor.id ? null : tutor.id; openDropdownId = null"
                  class="flex items-center justify-between w-full min-w-[140px] px-3 py-1.5 rounded-lg border transition-all text-sm font-medium"
                  :class="[
                    tutor.working_status === 'stopped' ? 'bg-red-100 border-red-200 text-red-800 hover:bg-red-200' : 
                    tutor.working_status === 'part_time' ? 'bg-green-100 border-green-200 text-green-800 hover:bg-green-200' :
                    tutor.working_status === 'full_time' ? 'bg-purple-100 border-purple-200 text-purple-800 hover:bg-purple-200' : 
                    'bg-gray-100 border-gray-200 text-gray-700 hover:bg-gray-200'
                  ]"
                >
                  <span>{{ 
                    tutor.working_status === 'stopped' ? 'No Classes' : 
                    tutor.working_status === 'part_time' ? 'Part Time' : 'Full Time'
                  }}</span>
                  <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': statusDropdownId === tutor.id }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <!-- Status Dropdown Menu -->
                <div
                  v-if="statusDropdownId === tutor.id"
                  class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1 overflow-hidden transition-all"
                >
                  <button
                    @click="updateWorkingStatus(tutor, 'full_time'); statusDropdownId = null"
                    class="w-full text-left px-4 py-2.5 hover:bg-purple-50 flex items-center gap-3 transition-colors"
                    :class="{ 'bg-purple-50': tutor.working_status === 'full_time' }"
                  >
                    <div class="w-2.5 h-2.5 rounded-full bg-purple-400"></div>
                    <span class="text-sm font-medium text-gray-700">Full Time</span>
                  </button>
                  <button
                    @click="updateWorkingStatus(tutor, 'part_time'); statusDropdownId = null"
                    class="w-full text-left px-4 py-2.5 hover:bg-green-50 flex items-center gap-3 transition-colors"
                    :class="{ 'bg-green-50': tutor.working_status === 'part_time' }"
                  >
                    <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                    <span class="text-sm font-medium text-gray-700">Part Time</span>
                  </button>
                  <button
                    @click="updateWorkingStatus(tutor, 'stopped'); statusDropdownId = null"
                    class="w-full text-left px-4 py-2.5 hover:bg-red-50 flex items-center gap-3 transition-colors"
                    :class="{ 'bg-red-50': tutor.working_status === 'stopped' }"
                  >
                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                    <span class="text-sm font-medium text-gray-700">No Classes</span>
                  </button>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <!-- Primary Actions -->
                <button
                  @click="openStats(tutor)"
                  title="View Stats"
                  class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors group relative"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                  <span class="absolute hidden group-hover:block bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs bg-gray-900 text-white rounded whitespace-nowrap">Stats</span>
                </button>


                <!-- More Actions Dropdown -->
                <div class="relative" @click.stop>
                  <button
                    @click="toggleDropdown(tutor.id); statusDropdownId = null"
                    class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors border border-gray-300"
                    title="More Actions"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                  </button>
                  
                  <!-- Dropdown Menu -->
                  <div
                    v-if="openDropdownId === tutor.id"
                    class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10"
                  >
                    <button
                      @click="editTutor(tutor); closeDropdown()"
                      class="w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2 text-sm text-gray-700 rounded-t-lg"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Edit
                    </button>
                    <button
                      @click="deleteTutor(tutor.id); closeDropdown()"
                      class="w-full text-left px-4 py-2 hover:bg-red-50 flex items-center gap-2 text-sm text-red-600 rounded-b-lg"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loading && filteredTutors.length === 0" class="p-8 text-center">
        <p class="text-gray-500">No tutors found</p>
      </div>
    </div>

    <!-- Add/Edit Form Modal -->
    <div
      v-if="showAddForm || editingTutor"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
          {{ editingTutor ? 'Edit Tutor' : 'Add New Tutor' }}
        </h2>

        <form @submit.prevent="saveTutor" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">First Name</label>
              <input
                v-model="formData.first_name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Last Name</label>
            <input
                v-model="formData.last_name"
              type="text"
              
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
            </div>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
            <input
              v-model="formData.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Phone</label>
            <input
              v-model="formData.phone"
              type="text"
              placeholder="Enter phone number"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Biography</label>
            <textarea
              v-model="formData.biography"
              rows="3"
              placeholder="Enter biography"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            ></textarea>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Password
              <span v-if="editingTutor" class="text-xs text-gray-500">(leave empty to keep current password)</span>
            </label>
            <input
              v-model="formData.password"
              type="password"
              :required="!editingTutor"
              placeholder="Enter password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
            <input
              v-model="formData.confirmPassword"
              type="password"
              :required="formData.password.length > 0"
              placeholder="Confirm password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
            <p v-if="formData.password && formData.password !== formData.confirmPassword" class="text-red-500 text-xs mt-1">
              Passwords do not match
            </p>
          </div>

          <div class="flex items-center">
            <input
              v-model="formData.isActive"
              type="checkbox"
              id="isActive"
              class="w-4 h-4 text-[#0055A4]"
            />
            <label for="isActive" class="ml-2 text-gray-700 text-sm">
              Active Tutor
            </label>
          </div>

          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="isSaving"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <span v-if="isSaving" class="inline-block animate-spin">⌛</span>
              {{ isSaving ? 'Saving...' : (editingTutor ? 'Update' : 'Add') }}
            </button>
            <button
              type="button"
              @click="cancelForm"
              :disabled="isSaving"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tutor Stats Modal -->
    <TutorStatsModal
      :show="showStatsModal"
      :tutor-id="selectedTutorId"
      :tutor-name="selectedTutorName"
      @close="closeStatsModal"
    />

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'
import TutorStatsModal from '../../components/TutorStatsModal.vue'

const toast = useToast()
const searchQuery = ref('')
const filterStatus = ref('')
const showAddForm = ref(false)
const editingTutor = ref(null)
const loading = ref(false)
const isSaving = ref(false)
const showStatsModal = ref(false)
const selectedTutorId = ref(null)
const selectedTutorName = ref('')
const openDropdownId = ref(null)
const statusDropdownId = ref(null)

const tutors = ref([])

const loadTutors = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/tutors', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      const tutorsData = response.data.data.data || response.data.data || []
      tutors.value = tutorsData.map(tutor => ({
        id: tutor.id,
        name: tutor.name || `${tutor.first_name || ''} ${tutor.last_name || ''}`.trim(),
        email: tutor.email,
        phone: tutor.phone || '',
        students: 0, // Placeholder
        courses: 0, // Placeholder
        joinedDate: new Date(tutor.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
        isActive: true, // Placeholder
        avatar: tutor.profile_picture,
        biography: tutor.biography || '',
        working_status: tutor.working_status || 'part_time',
        pending_timer_edits: tutor.pending_timer_edits || 0
      })).sort((a, b) => a.name.localeCompare(b.name))
    }
  } catch (error) {
    console.error('Failed to load tutors:', error)
    toast.error('Failed to load tutors')
  } finally {
    loading.value = false
  }
}

const formData = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  biography: '',
  password: '',
  confirmPassword: '',
  isActive: true
})

const filteredTutors = computed(() => {
  const filtered = tutors.value.filter(tutor => {
    const matchesSearch = tutor.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         tutor.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    // Handle status filtering
    let matchesStatus = true
    if (filterStatus.value) {
      if (filterStatus.value === 'stopped') {
        matchesStatus = tutor.working_status === 'stopped'
      } else if (filterStatus.value === 'part_time') {
        matchesStatus = tutor.working_status === 'part_time'
      } else if (filterStatus.value === 'full_time') {
        matchesStatus = tutor.working_status === 'full_time'
      }
    }
    
    return matchesSearch && matchesStatus
  })
  // Sort alphabetically by name
  return filtered.sort((a, b) => a.name.localeCompare(b.name))
})

const editTutor = (tutor) => {
  editingTutor.value = tutor
  // Extract first_name and last_name from name if they exist
  let first_name = tutor.first_name || ''
  let last_name = tutor.last_name || ''
  
  // If name exists but first_name/last_name don't, try to split the name
  if (!first_name && !last_name && tutor.name) {
    const nameParts = tutor.name.trim().split(/\s+/)
    if (nameParts.length > 0) {
      first_name = nameParts[0]
      last_name = nameParts.slice(1).join(' ') || ''
    }
  }
  
  formData.value = {
    first_name: first_name,
    last_name: last_name,
    email: tutor.email,
    phone: tutor.phone || '',
    biography: tutor.biography || '',
    password: '',
    confirmPassword: '',
    isActive: tutor.isActive
  }
  showAddForm.value = false
}

const saveTutor = async () => {
  // Validate password match
  if (formData.value.password && formData.value.password !== formData.value.confirmPassword) {
    toast.error('Passwords do not match')
    return
  }

  // Validate password length
  if (formData.value.password && formData.value.password.length < 6) {
    toast.error('Password must be at least 6 characters long')
    return
  }

  isSaving.value = true
  try {
    const payload = {
      first_name: formData.value.first_name,
      last_name: formData.value.last_name,
      email: formData.value.email,
      phone: formData.value.phone || null,
      biography: formData.value.biography || null
    }

    // Only include password if provided
    if (formData.value.password) {
      payload.password = formData.value.password
    }

    if (editingTutor.value) {
      // Update existing tutor
      await axios.put(`/api/admin/tutors/${editingTutor.value.id}`, payload, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      toast.success('Tutor updated successfully')
    } else {
      // Add new tutor
      const response = await axios.post('/api/admin/tutors', payload, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      toast.success('Tutor added successfully')
    }

    // Reload tutors list
    await loadTutors()
    cancelForm()
  } catch (error) {
    console.error('Error saving tutor:', error)
    toast.error(error.response?.data?.message || 'Failed to save tutor')
  } finally {
    isSaving.value = false
  }
}

const deleteTutor = async (tutorId) => {
  if (confirm('Are you sure you want to delete this tutor?')) {
    try {
      await axios.delete(`/api/admin/tutors/${tutorId}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      tutors.value = tutors.value.filter(t => t.id !== tutorId)
      toast.success('Tutor deleted successfully')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Failed to delete tutor')
    }
  }
}

const cancelForm = () => {
  showAddForm.value = false
  editingTutor.value = null
  formData.value = {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    biography: '',
    password: '',
    confirmPassword: '',
    isActive: true
  }
}

const getInitials = (tutor) => {
  if (!tutor) return 'U'
  const name = tutor.name || `${tutor.first_name || ''} ${tutor.last_name || ''}`.trim() || tutor.email || 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const openStats = (tutor) => {
  selectedTutorId.value = tutor.id
  selectedTutorName.value = tutor.name
  showStatsModal.value = true
}

const closeStatsModal = () => {
  showStatsModal.value = false
  selectedTutorId.value = null
  selectedTutorName.value = ''
}

const updateWorkingStatus = async (tutor, newStatus) => {
  try {
    const response = await axios.post(`/api/admin/tutors/${tutor.id}/update-status`, {
      status: newStatus
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    if (response.data.success) {
      tutor.working_status = response.data.data.working_status
      toast.success(response.data.message)
    }
  } catch (error) {
    console.error('Error updating status:', error)
    toast.error('Failed to update status')
  }
}

const toggleDropdown = (tutorId) => {
  openDropdownId.value = openDropdownId.value === tutorId ? null : tutorId
}

const closeDropdown = () => {
  openDropdownId.value = null
  statusDropdownId.value = null
}

onMounted(() => {
  loadTutors()
  
  // Close dropdown when clicking outside
  document.addEventListener('click', closeDropdown)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown)
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
