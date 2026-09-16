<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showAddForm = true"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add User
      </button>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
      <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
          <button
            @click="filterType = 'all'"
            :class="filterType === 'all' ? 'border-[#0055A4] text-[#0055A4]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors"
          >
            All Users
          </button>
          <button
            @click="filterType = 'super_admin'"
            :class="filterType === 'super_admin' ? 'border-[#0055A4] text-[#0055A4]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors"
          >
            Super Admins
          </button>
          <button
            @click="filterType = 'admin'"
            :class="filterType === 'admin' ? 'border-[#0055A4] text-[#0055A4]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors"
          >
            Admins
          </button>
          <button
            @click="filterType = 'tutor'"
            :class="filterType === 'tutor' ? 'border-[#0055A4] text-[#0055A4]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors"
          >
            Tutors
          </button>
        </nav>
      </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search users by name or email..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
      />
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading users...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Permissions</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-if="filteredUsers.length === 0" class="bg-white">
            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
              No users found
            </td>
          </tr>
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-[#0055A4]/10 flex items-center justify-center">
                  <span class="text-[#0055A4] font-semibold">
                    {{ (user.first_name?.[0] || user.name?.[0] || 'U').toUpperCase() }}
                  </span>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ user.first_name }} {{ user.last_name }}
                  </div>
                  <div class="text-sm text-gray-500">{{ user.username }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ user.email }}</td>
            <td class="px-6 py-4">
              <span
                :class="
                  user.user_type === 'super_admin'
                    ? 'bg-red-100 text-red-800'
                    : user.user_type === 'admin'
                    ? 'bg-[#0055A4]/10 text-[#003d7a]'
                    : 'bg-blue-100 text-blue-800'
                "
                class="px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ user.user_type === 'super_admin' ? 'Super Admin' : user.user_type === 'admin' ? 'Admin' : 'Tutor' }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ user.phone || '-' }}</td>
            <td class="px-6 py-4">
              <div class="flex flex-col items-start gap-1.5">
                <template v-for="(value, key) in user.permissions || {}" :key="key">
                  <span
                    v-if="value"
                    :class="getPermissionBadgeClass(key)"
                    class="px-2.5 py-1 text-xs font-semibold rounded-md border shadow-sm"
                  >
                    {{ permissionLabels[key] || key }}
                  </span>
                </template>
                <span v-if="!hasActivePermissions(user)" class="text-xs text-gray-400 italic">
                  No permissions
                </span>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ new Date(user.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 text-sm font-medium">
              <div class="flex gap-2">
                <button
                  @click="editUser(user)"
                  class="text-[#0055A4] hover:text-[#003d7a] transition-colors"
                  :disabled="actionLoading[user.id]"
                >
                  <span v-if="actionLoading[user.id]" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-[#0055A4]"></span>
                  <span v-else>Edit</span>
                </button>
                <button
                  @click="deleteUser(user.id)"
                  class="text-red-600 hover:text-red-900 transition-colors"
                  :disabled="actionLoading[user.id]"
                >
                  <span v-if="actionLoading[user.id]" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-red-600"></span>
                  <span v-else>Delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </div>
        <div class="flex gap-2">
          <button
            @click="loadUsers(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button
            @click="loadUsers(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddForm || editingUser" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">
          {{ editingUser ? 'Edit User' : 'Add New User' }}
        </h3>

        <form @submit.prevent="saveUser" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
              <input
                v-model="form.first_name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
              <input
                v-model="form.last_name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password {{ editingUser ? '(leave blank to keep current)' : '*' }}</label>
            <input
              v-model="form.password"
              type="password"
              :required="!editingUser"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">User Type *</label>
              <select
                v-model="form.user_type"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="">Select Type</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="tutor">Tutor</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>

          <!-- Permissions Section -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
            <div class="grid grid-cols-1 gap-3">
              <label class="flex items-center space-x-2">
                <input
                  v-model="form.permissions.hide_total_revenue"
                  type="checkbox"
                  class="rounded border-gray-300 text-[#0055A4] focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <span class="text-sm text-gray-700">Hide Total Revenue</span>
              </label>
              <label class="flex items-center space-x-2">
                <input
                  v-model="form.permissions.hide_manage_users"
                  type="checkbox"
                  class="rounded border-gray-300 text-[#0055A4] focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <span class="text-sm text-gray-700">Hide Manage Users</span>
              </label>
              <label class="flex items-center space-x-2">
                <input
                  v-model="form.permissions.hide_tutor_pay"
                  type="checkbox"
                  class="rounded border-gray-300 text-[#0055A4] focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <span class="text-sm text-gray-700">Hide Tutor Pay</span>
              </label>
              <label class="flex items-center space-x-2">
                <input
                  v-model="form.permissions.hide_attendance"
                  type="checkbox"
                  class="rounded border-gray-300 text-[#0055A4] focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <span class="text-sm text-gray-700">Hide Attendance</span>
              </label>
              <label class="flex items-center space-x-2">
                <input
                  v-model="form.permissions.hide_tutors"
                  type="checkbox"
                  class="rounded border-gray-300 text-[#0055A4] focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <span class="text-sm text-gray-700">Hide Tutors Tracker</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-4 pt-4 border-t">
            <button
              type="button"
              @click="closeModal"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors disabled:opacity-50"
            >
              <span v-if="saving" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
              <span v-else>{{ editingUser ? 'Update' : 'Create' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useToast } from '../../composables/useToast'

const toast = useToast()
const loading = ref(false)
const saving = ref(false)
const showAddForm = ref(false)
const editingUser = ref(null)
const permissionLabels = {
  hide_total_revenue: 'Hide Total Revenue',
  hide_manage_users: 'Hide Manage Users',
  hide_tutor_pay: 'Hide Tutor Pay',
  hide_attendance: 'Hide Attendance',
  hide_tutors: 'Hide Tutors Tracker'
}
const filterType = ref('all')
const searchQuery = ref('')
const users = ref([])
const pagination = ref(null)
const actionLoading = ref({})

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  permissions: {
    hide_total_revenue: false,
    hide_manage_users: false,
    hide_tutor_pay: false,
    hide_attendance: false,
    hide_tutors: false
  },
  password: '',
  user_type: ''
})

const hasActivePermissions = (user) => {
  if (!user.permissions) return false
  return Object.values(user.permissions).some(val => val === true)
}

const getPermissionBadgeClass = (key) => {
  switch (key) {
    case 'hide_total_revenue':
      return 'bg-amber-50 text-amber-700 border-amber-200'
    case 'hide_manage_users':
      return 'bg-purple-50 text-purple-700 border-purple-200'
    case 'hide_tutor_pay':
      return 'bg-rose-50 text-rose-700 border-rose-200'
    case 'hide_attendance':
      return 'bg-blue-50 text-blue-700 border-blue-200'
    case 'hide_tutors':
      return 'bg-indigo-50 text-indigo-700 border-indigo-200'
    default:
      return 'bg-green-50 text-green-700 border-green-200'
  }
}

const filteredUsers = computed(() => {
  let filtered = users.value

  // Filter by type
  if (filterType.value !== 'all') {
    filtered = filtered.filter(user => user.user_type === filterType.value)
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(user => {
      const fullName = `${user.first_name} ${user.last_name}`.toLowerCase()
      return fullName.includes(query) || user.email.toLowerCase().includes(query)
    })
  }

  // Sort alphabetically by first name
  filtered = [...filtered].sort((a, b) => {
    const nameA = (a.first_name || '').toLowerCase()
    const nameB = (b.first_name || '').toLowerCase()
    return nameA.localeCompare(nameB)
  })

  return filtered
})

const loadUsers = async (page = 1) => {
  loading.value = true
  try {
    const response = await axios.get(`/api/admin/manage-users?type=${filterType.value}&page=${page}`)
    users.value = response.data.data.data || []
    pagination.value = {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      from: response.data.data.from,
      to: response.data.data.to,
      total: response.data.data.total
    }
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to load users')
  } finally {
    loading.value = false
  }
}

const editUser = (user) => {
  editingUser.value = user
  form.value = {
    first_name: user.first_name || '',
    last_name: user.last_name || '',
    email: user.email || '',
    password: '',
    user_type: user.user_type || '',
    phone: user.phone || '',
    permissions: {
      hide_total_revenue: user.permissions?.hide_total_revenue || false,
      hide_manage_users: user.permissions?.hide_manage_users || false,
      hide_tutor_pay: user.permissions?.hide_tutor_pay || false,
      hide_attendance: user.permissions?.hide_attendance || false,
      hide_tutors: user.permissions?.hide_tutors || false
    }
  }
  showAddForm.value = true
}

const saveUser = async () => {
  saving.value = true
  try {
    const payload = { ...form.value }
    
    // Remove password if empty (for updates)
    if (editingUser.value && !payload.password) {
      delete payload.password
    }

    // Convert permissions object to proper format
    const permissions = {}
    Object.keys(payload.permissions).forEach(key => {
      if (payload.permissions[key]) {
        permissions[key] = true
      }
    })
    payload.permissions = permissions

    if (editingUser.value) {
      await axios.put(`/api/admin/manage-users/${editingUser.value.id}`, payload)
      toast.success('User updated successfully')
    } else {
      await axios.post('/api/admin/manage-users', payload)
      toast.success('User created successfully')
    }

    closeModal()
    loadUsers(pagination.value?.current_page || 1)
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to save user')
  } finally {
    saving.value = false
  }
}

const deleteUser = async (userId) => {
  if (!confirm('Are you sure you want to delete this user?')) return

  actionLoading.value[userId] = true
  try {
    await axios.delete(`/api/admin/manage-users/${userId}`)
    toast.success('User deleted successfully')
    loadUsers(pagination.value?.current_page || 1)
  } catch (error) {
    toast.error(error.response?.data?.message || 'Failed to delete user')
  } finally {
    actionLoading.value[userId] = false
  }
}

const closeModal = () => {
  showAddForm.value = false
  editingUser.value = null
  form.value = {
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    user_type: '',
    phone: '',
    permissions: {
      hide_total_revenue: false,
      hide_manage_users: false,
      hide_tutor_pay: false,
      hide_attendance: false,
      hide_tutors: false
    }
  }
}

// Watch filter type changes
watch(filterType, () => {
  loadUsers(1)
})

onMounted(() => {
  loadUsers()
})
</script>

