<template>
  <div class="">
    <div class="flex justify-end items-center gap-3 mb-6">
      <button
        @click="exportStudents"
        :disabled="exporting"
        class="px-5 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-medium transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
        :title="`Export current filtered list to Excel (CSV)`"
      >
        <svg v-if="!exporting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
        </svg>
        <svg v-else class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ exporting ? 'Exporting...' : 'Export to Excel' }}
      </button>
      <button
        @click="showAddForm = true"
        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors"
      >
        + Add Student
      </button>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
        <!-- Search -->
        <div class="lg:col-span-1">
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Search Students</label>
          <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Name or email..."
              class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all"
            />
          </div>
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Status</label>
          <select 
            v-model="filters.status"
            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all appearance-none"
          >
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
            <option value="na">N/A</option>
          </select>
        </div>

        <!-- Payment Filter -->
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Login Access</label>
          <select
            v-model="filters.payment_status"
            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all appearance-none"
          >
            <option value="">All Access</option>
            <option value="paid">Granted</option>
            <option value="unpaid">Revoked</option>
          </select>
        </div>

        <!-- Tutor Filter -->
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Tutor Assignment</label>
          <select 
            v-model="filters.tutor_assignment"
            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all appearance-none"
          >
            <option value="">All Assignments</option>
            <option value="assigned">Assigned Only</option>
            <option value="unassigned">Unassigned Only</option>
          </select>
        </div>

        <!-- Year Filter -->
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Enrollment Year</label>
          <select
            v-model="filters.year"
            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all appearance-none"
          >
            <option value="">All Years</option>
            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>

        <!-- Exam Prep Access Filter -->
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Exam Prep Access</label>
          <select
            v-model="filters.exam_prep_access"
            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all appearance-none"
          >
            <option value="">All Exam Prep</option>
            <option value="full">Full Access</option>
            <option value="partial">Partial Access</option>
            <option value="any">Any Access</option>
            <option value="none">No Access</option>
          </select>
        </div>
      </div>

      <div v-if="selectedStudents.size > 0" class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
        <button
          @click="deleteBulk"
          class="px-6 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl font-bold transition-all flex items-center gap-2"
        >
          <i class="fas fa-trash"></i>
          Delete Selected ({{ selectedStudents.size }})
        </button>
      </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto min-h-[500px]">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
        <p class="mt-2 text-gray-500">Loading students...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
              <input
                type="checkbox"
                :checked="selectedStudents.size === filteredStudents.length && filteredStudents.length > 0"
                @change="toggleSelectAll"
                class="w-4 h-4 text-green-600"
              />
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Entry ID</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Enrolled</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Assigned Tutor</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Login Access</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in filteredStudents" :key="student.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4">
              <input
                type="checkbox"
                :checked="selectedStudents.has(student.id)"
                @change="toggleStudent(student.id)"
                class="w-4 h-4 text-green-600"
              />
            </td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ student.entryId }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0">
                  <span class="text-xs font-bold text-[#0055A4]">
                    {{ getInitials(student) }}
                  </span>
                </div>
                <div class="flex items-center gap-2">
                  <button
                    @click="viewStudent(student)"
                    class="font-medium text-gray-800 hover:text-[#0055A4] hover:underline cursor-pointer transition-colors text-left"
                  >
                    {{ student.name }}
                  </button>
                  <span
                    v-if="student.hasImportedEnrollment"
                    class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full"
                    title="Has Manually Imported Enrollments"
                  >
                    Imported
                  </span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700">{{ student.email }}</td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ student.enrollmentDate }}</td>
            <td class="px-6 py-4">
              <div class="relative inline-tutor-dropdown">
                <button
                  @click.stop="toggleInlineTutorDropdown(student.id)"
                  class="min-w-[140px] px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0055A4] flex items-center justify-between gap-2"
                >
                  <span :class="student.tutorId ? 'text-gray-800' : 'text-gray-500'">
                    {{ getStudentTutorName(student) }}
                  </span>
                  <svg
                    class="w-4 h-4 text-gray-400 transition-transform"
                    :class="{ 'rotate-180': openTutorDropdownId === student.id }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>
                <div
                  v-if="openTutorDropdownId === student.id"
                  class="absolute z-50 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg"
                  @click.stop
                >
                  <div class="p-2 border-b border-gray-100">
                    <input
                      v-model="inlineTutorSearchQuery"
                      type="text"
                      placeholder="Search tutor..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4] text-sm"
                      @click.stop
                    />
                  </div>
                  <ul class="max-h-48 overflow-y-auto py-1">
                    <li
                      @click="selectInlineTutor(student.id, null)"
                      class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                      :class="!student.tutorId ? 'bg-green-50 text-green-700' : 'text-gray-500'"
                    >
                      No Tutor
                    </li>
                    <li
                      v-for="tutor in inlineFilteredTutors"
                      :key="tutor.id"
                      @click="selectInlineTutor(student.id, tutor.id)"
                      class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                      :class="student.tutorId === tutor.id ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700'"
                    >
                      {{ tutor.name }}
                    </li>
                    <li v-if="inlineFilteredTutors.length === 0 && inlineTutorSearchQuery" class="px-4 py-2 text-gray-400 text-sm">
                      No tutors found
                    </li>
                  </ul>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span
                v-if="student.shouldShowNA"
                class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600"
              >
                N/A
              </span>
              <span
                v-else-if="student.status === 'Active'"
                class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"
              >
                {{ student.isManualActivation ? 'Active (Manual)' : 'Active' }}
              </span>
              <span
                v-else-if="student.status === 'Processing'"
                class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
              >
                {{ student.status }}
              </span>
              <span
                v-else-if="student.status === 'Pending'"
                class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
              >
                {{ student.status }}
              </span>
              <span
                v-else-if="student.status === 'Cancelled'"
                class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"
              >
                {{ student.status }}
              </span>
              <span
                v-else
                :class="student.isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ student.status || (student.isActive ? 'Active' : 'Inactive') }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button
                @click="togglePaymentConfirmed(student)"
                :disabled="student.togglingPayment"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                :class="student.paymentConfirmed ? 'bg-green-600' : 'bg-gray-200'"
                role="switch"
                :aria-checked="student.paymentConfirmed"
                :title="student.paymentConfirmed ? 'Login access granted - Click to revoke' : 'Login access pending - Click to grant'"
              >
                <span
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                  :class="student.paymentConfirmed ? 'translate-x-5' : 'translate-x-0'"
                ></span>
              </button>
            </td>
            <td class="px-6 py-4">
              <div class="relative inline-block text-left" v-click-outside="() => closeDropdown(student.id)">
                <button
                  @click="toggleDropdown(student.id)"
                  class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors border border-gray-300"
                >
                  Actions
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div
                  v-if="openDropdownId === student.id"
                  class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                >
                  <button
                    @click="viewStudent(student); closeDropdown(student.id)"
                    class="w-full px-4 py-2 text-left text-sm text-blue-600 hover:bg-blue-50 flex items-center gap-2 rounded-t-lg"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                  </button>
                  <button
                    @click="editStudent(student); closeDropdown(student.id)"
                    class="w-full px-4 py-2 text-left text-sm text-yellow-600 hover:bg-yellow-50 flex items-center gap-2"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                  </button>
                  <button
                    @click="openRecords(student); closeDropdown(student.id)"
                    class="w-full px-4 py-2 text-left text-sm text-purple-600 hover:bg-purple-50 flex items-center gap-2"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Records
                  </button>
                  <button
                    @click="openExamPrepAccess(student); closeDropdown(student.id)"
                    class="w-full px-4 py-2 text-left text-sm flex items-center gap-2"
                    :class="getGrantedCount(student.id) > 0 ? 'text-green-700 hover:bg-green-50' : 'text-[#0055A4] hover:bg-orange-50'"
                  >
                    <!-- Unlock icon when access granted -->
                    <svg v-if="getGrantedCount(student.id) > 0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    <!-- Locked icon when no access -->
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    {{ examPrepAccessLabel(student.id) }}
                  </button>
                  <button
                    @click="deleteStudent(student.id); closeDropdown(student.id)"
                    class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 rounded-b-lg"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                  </button>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loading && filteredStudents.length === 0" class="p-8 text-center">
        <p class="text-gray-500">No students found</p>
      </div>

      <!-- Pagination Controls -->
      <div v-if="pagination.last_page > 1 || pagination.total > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-600 flex items-center gap-4">
          <div>
            Showing <span class="font-semibold">{{ (pagination.current_page - 1) * pagination.per_page + 1 }}</span>
            to <span class="font-semibold">{{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}</span>
            of <span class="font-semibold">{{ pagination.total }}</span> students
          </div>
          <div class="flex items-center gap-2">
            <label for="perPage" class="text-sm font-medium text-gray-700">Show:</label>
            <select
              id="perPage"
              :value="perPage"
              @change="changePerPage(parseInt($event.target.value))"
              class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
              <option :value="150">150</option>
              <option :value="200">200</option>
            </select>
            <span class="text-sm text-gray-700">per page</span>
          </div>
        </div>
        <div class="flex gap-2">
          <button
            @click="loadStudents(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100"
          >
            Previous
          </button>
          <div class="flex items-center gap-1">
            <button
              v-for="page in pagesToShow"
              :key="`page-${page}`"
              :disabled="page === '...'"
              @click="page !== '...' && loadStudents(page)"
              :class="[
                page === '...' ? 'cursor-default text-gray-400' : (currentPage === page ? 'bg-green-600 text-white' : 'border border-gray-300 hover:bg-gray-100'),
                page === '...' ? '' : 'transition-colors'
              ]"
              class="px-3 py-2 rounded-lg text-sm font-medium"
            >
              {{ page }}
            </button>
          </div>
          <button
            @click="loadStudents(currentPage + 1)"
            :disabled="currentPage === pagination.last_page"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Form Modal -->
    <div
      v-if="showAddForm || editingStudent"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
          {{ editingStudent ? 'Edit Student' : 'Add New Student' }}
        </h2>

        <form @submit.prevent="saveStudent" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">First Name</label>
            <input
              v-model="formData.firstName"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Last Name</label>
            <input
              v-model="formData.lastName"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
            <input
              v-model="formData.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <!-- Password fields (only for new students) -->
          <div v-if="!editingStudent">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input
              v-model="formData.password"
              type="password"
              :required="!editingStudent"
              minlength="8"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Minimum 8 characters"
            />
          </div>

          <div v-if="!editingStudent">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
            <input
              v-model="formData.confirmPassword"
              type="password"
              :required="!editingStudent"
              minlength="8"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Re-enter password"
            />
            <p v-if="formData.password && formData.confirmPassword && formData.password !== formData.confirmPassword" class="text-red-500 text-xs mt-1">
              Passwords do not match
            </p>
          </div>

          <div class="relative tutor-dropdown-container">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Assign Tutor</label>
            <div
              @click="showTutorDropdown = !showTutorDropdown"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg cursor-pointer bg-white flex justify-between items-center"
            >
              <span :class="formData.tutorId ? 'text-gray-800' : 'text-gray-500'">{{ selectedTutorName }}</span>
              <svg class="w-4 h-4 text-gray-400" :class="{ 'rotate-180': showTutorDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>
            <div
              v-if="showTutorDropdown"
              class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg"
            >
              <div class="p-2 border-b border-gray-200">
                <input
                  v-model="tutorSearchQuery"
                  type="text"
                  placeholder="Search tutor..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                  @click.stop
                />
              </div>
              <ul class="max-h-48 overflow-y-auto">
                <li
                  @click="selectTutor('')"
                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-500"
                  :class="{ 'bg-green-50': !formData.tutorId }"
                >
                  No Tutor
                </li>
                <li
                  v-for="tutor in filteredTutors"
                  :key="tutor.id"
                  @click="selectTutor(tutor.id)"
                  class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                  :class="{ 'bg-green-50 text-green-700': formData.tutorId === tutor.id }"
                >
                  {{ tutor.name }}
                </li>
                <li v-if="filteredTutors.length === 0 && tutorSearchQuery" class="px-4 py-2 text-gray-500 text-sm">
                  No tutors found
                </li>
              </ul>
            </div>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Enrollment Status</label>
            <select
              v-model="formData.status"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white"
            >
              <option value="Active">Active</option>
              <option value="Pending">Pending</option>
              <option value="Processing">Processing</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>

          <div class="flex items-center">
            <input
              v-model="formData.isActive"
              type="checkbox"
              id="isActive"
              class="w-4 h-4 text-green-600"
            />
            <label for="isActive" class="ml-2 text-gray-700 text-sm">
              Portal Access
            </label>
          </div>

          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="savingStudent || (!editingStudent && formData.password !== formData.confirmPassword)"
              class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="savingStudent" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ savingStudent ? 'Saving...' : (editingStudent ? 'Update' : 'Add') }}
            </button>
            <button
              type="button"
              @click="cancelForm"
              :disabled="savingStudent"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 disabled:opacity-50 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Exam Prep Access Modal -->
    <div
      v-if="examPrepModalOpen"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeExamPrepAccess"
    >
      <div class="bg-white rounded-2xl max-w-lg w-full max-h-[85vh] flex flex-col shadow-2xl">
        <div class="p-6 border-b border-gray-100">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-xl font-bold text-gray-800">Exam Prep Access</h3>
              <p class="text-sm text-gray-600 mt-1">{{ examPrepModalStudentName }}</p>
            </div>
            <button @click="closeExamPrepAccess" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
          <div v-if="examPrepLoading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0055A4]"></div>
            <p class="mt-2 text-gray-500 text-sm">Loading exam preps...</p>
          </div>

          <div v-else-if="examPrepList.length === 0" class="text-center py-8 text-gray-500">
            <p>No exam preps available.</p>
          </div>

          <template v-else>
            <div class="flex items-center justify-between gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
              <span class="text-sm text-gray-600">
                {{ examPrepGrantedCount }} of {{ examPrepList.length }} granted
              </span>
              <div class="flex gap-2">
                <button
                  @click="grantAllExamPreps"
                  :disabled="examPrepBulk || examPrepAllGranted"
                  class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors"
                  :class="examPrepAllGranted
                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                    : 'bg-[#0055A4] hover:bg-[#003d7a] text-white'"
                >
                  {{ examPrepBulk === 'grant' ? 'Granting...' : 'Grant All' }}
                </button>
                <button
                  @click="revokeAllExamPreps"
                  :disabled="examPrepBulk || examPrepGrantedCount === 0"
                  class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors"
                  :class="examPrepGrantedCount === 0
                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                    : 'bg-red-500 hover:bg-red-600 text-white'"
                >
                  {{ examPrepBulk === 'revoke' ? 'Revoking...' : 'Revoke All' }}
                </button>
              </div>
            </div>

            <div class="space-y-2">
              <div
                v-for="ep in examPrepList"
                :key="ep.id"
                class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50"
              >
                <div class="min-w-0">
                  <p class="font-medium text-gray-800 truncate">{{ ep.title }}</p>
                  <p v-if="ep.category" class="text-xs text-gray-500 mt-0.5">{{ ep.category }}</p>
                </div>
                <label class="inline-flex items-center cursor-pointer flex-shrink-0">
                  <input
                    type="checkbox"
                    :checked="ep.has_access"
                    :disabled="examPrepRowSaving[ep.id]"
                    @change="toggleStudentExamPrep(ep)"
                    class="sr-only peer"
                  />
                  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#0055A4]/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0055A4]"></div>
                </label>
              </div>
            </div>
          </template>
        </div>

        <div class="p-4 border-t border-gray-100 flex justify-end">
          <button
            @click="closeExamPrepAccess"
            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors"
          >
            Done
          </button>
        </div>
      </div>
    </div>

    <!-- Student Records Modal -->
    <StudentRecordsModal
      :show="showRecordsModal"
      :student-id="selectedStudentId"
      :student-name="selectedStudentName"
      @close="closeRecordsModal"
    />

    <!-- View Student Modal -->
    <div
      v-if="showViewModal && viewingStudent"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 z-10">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Student Details</h2>
            <button
              @click="closeViewModal"
              class="text-gray-500 hover:text-gray-700 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6">
          <!-- Student Info -->
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0">
              <span class="text-2xl font-bold text-[#0055A4]">
                {{ getInitials(viewingStudent) }}
              </span>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-800">{{ viewingStudent.name }}</h3>
              <p class="text-gray-500">{{ viewingStudent.email }}</p>
            </div>
          </div>

          <!-- Details Grid -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Entry ID</p>
              <p class="font-medium text-gray-800">{{ viewingStudent.entryId || '-' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Enrollment Date</p>
              <p class="font-medium text-gray-800">{{ viewingStudent.enrollmentDate || '-' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Status</p>
              <span
                :class="{
                  'bg-green-100 text-green-800': viewingStudent.status === 'Active',
                  'bg-yellow-100 text-yellow-800': viewingStudent.status === 'Pending',
                  'bg-blue-100 text-blue-800': viewingStudent.status === 'Processing',
                  'bg-red-100 text-red-800': viewingStudent.status === 'Cancelled',
                  'bg-gray-100 text-gray-600': viewingStudent.shouldShowNA
                }"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ viewingStudent.shouldShowNA ? 'N/A' : (viewingStudent.isManualActivation && viewingStudent.status === 'Active' ? 'Active (Manual)' : viewingStudent.status) }}
              </span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Login Access</p>
              <span
                :class="viewingStudent.paymentConfirmed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ viewingStudent.paymentConfirmed ? 'Granted' : 'Pending' }}
              </span>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Assigned Tutor</p>
              <p class="font-medium text-gray-800">{{ viewingStudent.tutorName || 'Unassigned' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-500">Import Status</p>
              <span
                v-if="viewingStudent.hasImportedEnrollment"
                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium"
              >
                Imported
              </span>
              <span v-else class="text-gray-500">-</span>
            </div>
          </div>

          <!-- Enrollments Section -->
          <div v-if="viewingStudent.enrollments && viewingStudent.enrollments.length > 0">
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Enrollments</h4>
            <div class="space-y-2">
              <div
                v-for="enrollment in viewingStudent.enrollments"
                :key="enrollment.id"
                class="bg-gray-50 rounded-lg p-4 flex justify-between items-center"
              >
                <div>
                  <p class="font-medium text-gray-800">{{ enrollment.course?.course_title || 'Unknown Course' }}</p>
                  <p class="text-sm text-gray-500">{{ enrollment.class_type?.class_name || '-' }}</p>
                </div>
                <span
                  :class="{
                    'bg-green-100 text-green-800': enrollment.status === 'active',
                    'bg-yellow-100 text-yellow-800': enrollment.status === 'pending',
                    'bg-red-100 text-red-800': enrollment.status === 'cancelled'
                  }"
                  class="px-2 py-1 rounded-full text-xs font-medium capitalize"
                >
                  {{ enrollment.status || 'pending' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
          <div class="flex justify-end gap-3 flex-wrap">
            <button
              @click="openExamPrepAccess(viewingStudent); closeViewModal()"
              class="px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center gap-2"
              :class="getGrantedCount(viewingStudent.id) > 0
                ? 'bg-green-600 hover:bg-green-700 text-white'
                : 'bg-[#0055A4] hover:bg-[#003d7a] text-white'"
            >
              <svg v-if="getGrantedCount(viewingStudent.id) > 0" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              {{ examPrepAccessLabel(viewingStudent.id) }}
            </button>
            <button
              @click="editStudent(viewingStudent); closeViewModal()"
              class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition-colors"
            >
              Edit Student
            </button>
            <button
              @click="closeViewModal"
              class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Close
            </button>
          </div>
        </div>
        </div>
      </div>

    <!-- Status Activation Confirm Modal -->
    <div
      v-if="showStatusConfirmModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] py-8"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="p-8 text-center">
          <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Update Enrollment Status?</h3>
          <p class="text-gray-500 mb-8 px-4">
            You are granting portal access to <span class="font-semibold text-gray-700">{{ studentToUpdateStatus?.name }}</span>. 
            Would you also like to mark their enrollment as <span class="text-green-600 font-bold uppercase">Active</span>?
          </p>
          <div class="flex flex-col gap-3">
            <button
              @click="proceedWithToggle(true)"
              class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors"
            >
              Update & Activate
            </button>
            <button
              @click="proceedWithToggle(false)"
              class="w-full py-3 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
            >
              Just Grant Access
            </button>
            <button
              @click="cancelToggleStatus"
              class="w-full py-2 text-gray-400 hover:text-gray-600 text-sm font-medium transition-colors"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useToast } from '../../composables/useToast'
import axios from 'axios'
import StudentRecordsModal from '../../components/StudentRecordsModal.vue'

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el._clickOutside = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event)
      }
    }
    document.addEventListener('click', el._clickOutside)
  },
  unmounted(el) {
    document.removeEventListener('click', el._clickOutside)
  }
}

const toast = useToast()

const searchQuery = ref('')
const showAddForm = ref(false)
const editingStudent = ref(null)
const loading = ref(false)
const selectedStudents = ref(new Set())
const currentPage = ref(1)
const perPage = ref(15)
const showRecordsModal = ref(false)
const selectedStudentId = ref(null)
const selectedStudentName = ref('')
const viewingStudent = ref(null)
const openDropdownId = ref(null)
const showViewModal = ref(false)
const availableYears = ref([])
const showStatusConfirmModal = ref(false)
const studentToUpdateStatus = ref(null)
const exporting = ref(false)

const filters = ref({
  status: '',
  payment_status: '',
  tutor_assignment: '',
  year: '',
  exam_prep_access: ''
})

watch(filters, () => {
  loadStudents(1)
}, { deep: true })

const toggleDropdown = (studentId) => {
  openDropdownId.value = openDropdownId.value === studentId ? null : studentId
}

const closeDropdown = (studentId) => {
  if (openDropdownId.value === studentId) {
    openDropdownId.value = null
  }
}

const tutors = ref([])
const tutorSearchQuery = ref('')
const showTutorDropdown = ref(false)

// Inline tutor dropdown state (for table)
const openTutorDropdownId = ref(null)
const inlineTutorSearchQuery = ref('')

const filteredTutors = computed(() => {
  let result = tutors.value
  if (tutorSearchQuery.value.trim()) {
    const query = tutorSearchQuery.value.toLowerCase()
    result = result.filter(tutor =>
      tutor.name.toLowerCase().includes(query)
    )
  }
  // Sort alphabetically by name
  return [...result].sort((a, b) => a.name.localeCompare(b.name))
})

// Filtered tutors for inline dropdown
const inlineFilteredTutors = computed(() => {
  let result = tutors.value
  if (inlineTutorSearchQuery.value.trim()) {
    const query = inlineTutorSearchQuery.value.toLowerCase()
    result = result.filter(tutor =>
      tutor.name.toLowerCase().includes(query)
    )
  }
  // Sort alphabetically by name
  return [...result].sort((a, b) => a.name.localeCompare(b.name))
})

// Toggle inline tutor dropdown
const toggleInlineTutorDropdown = (studentId) => {
  if (openTutorDropdownId.value === studentId) {
    openTutorDropdownId.value = null
    inlineTutorSearchQuery.value = ''
  } else {
    openTutorDropdownId.value = studentId
    inlineTutorSearchQuery.value = ''
  }
}

// Close inline tutor dropdown
const closeInlineTutorDropdown = () => {
  openTutorDropdownId.value = null
  inlineTutorSearchQuery.value = ''
}

// Get tutor name for a student
const getStudentTutorName = (student) => {
  if (!student.tutorId) return 'No Tutor'
  const tutor = tutors.value.find(t => t.id === student.tutorId)
  return tutor ? tutor.name : 'No Tutor'
}

// Select tutor for inline dropdown
const selectInlineTutor = async (studentId, tutorId) => {
  const student = students.value.find(s => s.id === studentId)
  if (!student) return

  try {
    const response = await axios.post(
      `/api/admin/students/${studentId}/assign-tutor`,
      { tutor_id: tutorId || null }
    )

    if (response.data.success) {
      student.tutorId = tutorId || null
      toast.success(response.data.message || 'Tutor assigned successfully')
    }
  } catch (error) {
    console.error('Failed to assign tutor:', error)
    toast.error(error.response?.data?.message || 'Failed to assign tutor')
  }

  openTutorDropdownId.value = null
  inlineTutorSearchQuery.value = ''
}

const selectedTutorName = computed(() => {
  if (!formData.value.tutorId) return 'No Tutor'
  const tutor = tutors.value.find(t => t.id === formData.value.tutorId)
  return tutor ? tutor.name : 'No Tutor'
})

const selectTutor = (tutorId) => {
  formData.value.tutorId = tutorId
  showTutorDropdown.value = false
  tutorSearchQuery.value = ''
}

const students = ref([])
const pagination = ref({
  current_page: 1,
  per_page: 15,
  total: 0,
  last_page: 1
})

const loadStudents = async (page = 1) => {
  loading.value = true
  currentPage.value = page
  try {
    const params = new URLSearchParams({
      page: page,
      per_page: perPage.value
    })
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    
    // Add filters
    if (filters.value.status) params.append('status', filters.value.status)
    if (filters.value.payment_status) params.append('payment_status', filters.value.payment_status)
    if (filters.value.tutor_assignment) params.append('tutor_assignment', filters.value.tutor_assignment)
    if (filters.value.year) params.append('year', filters.value.year)
    
    const response = await axios.get(`/api/admin/students?${params.toString()}`)
    if (response.data.success) {
      if (response.data.available_years) {
          availableYears.value = response.data.available_years
      }
      students.value = response.data.data.data.map(student => {
        // Check if student has any imported enrollments
        const enrollments = student.enrollments || []
        const hasImportedEnrollment = enrollments.some(enrollment => {
          const formData = enrollment.form_data || {}
          return formData._imported === true || formData._imported === 'true' || formData._import_source === 'gravity_forms'
        })
        
        // Get latest enrollment for status display
        const latestEnrollment = enrollments.length > 0 ? enrollments[0] : null
        let status = 'Active'
        let statusDisplay = 'Active'
        let shouldShowNA = false
        let entryId = '-'
        
        if (latestEnrollment) {
          const enrollmentStatus = latestEnrollment.status || 'pending'
          const payment = latestEnrollment.payment
          const formData = latestEnrollment.form_data || {}
          const isImported = formData._imported === true || formData._imported === 'true' || formData._import_source === 'gravity_forms'
          
          // Get entry ID - use entry_id column if available, otherwise check form_data or enrollment.id
          if (latestEnrollment.entry_id) {
            entryId = latestEnrollment.entry_id.toString()
          } else if (isImported && formData.entry_id) {
            entryId = formData.entry_id.toString()
          } else if (latestEnrollment.id) {
            entryId = latestEnrollment.id.toString()
          }
          
          // For imported enrollments, check if we should show N/A
          if (isImported && (!payment || !payment.status || payment.status === 'pending' || payment.status === 'processing') && (enrollmentStatus === 'pending' || !enrollmentStatus)) {
            shouldShowNA = true
            statusDisplay = 'N/A'
          } else {
            status = enrollmentStatus.charAt(0).toUpperCase() + enrollmentStatus.slice(1)
            statusDisplay = status
          }
        } else {
          // If no enrollment, use student id as entry ID
          entryId = student.id.toString()
        }
        
        const isManualActivation = latestEnrollment?.form_data?._manual_activation === true || latestEnrollment?.form_data?._manual_activation === 'true'
        
        return {
          id: student.id,
          name: student.name || `${student.first_name} ${student.last_name}`,
          email: student.email,
          enrollmentDate: latestEnrollment ? new Date(latestEnrollment.enrollment_date || latestEnrollment.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '-',
          entryId: entryId,
          tutorId: student.tutor_id || null,
          isActive: student.payment_confirmed || false,
          avatar: student.profile_picture,
          hasImportedEnrollment: hasImportedEnrollment,
          status: statusDisplay,
          isManualActivation: isManualActivation,
          shouldShowNA: shouldShowNA,
          latestEnrollment: latestEnrollment,
          paymentConfirmed: student.payment_confirmed || false,
          togglingPayment: false
        }
      })

      // Store pagination metadata
      pagination.value = {
        current_page: response.data.data.current_page,
        per_page: response.data.data.per_page,
        total: response.data.data.total,
        last_page: response.data.data.last_page
      }

      // Clear selections when loading new page
      selectedStudents.value.clear()
    }
  } catch (error) {
    console.error('Failed to load students:', error)
    toast.error('Failed to load students')
  } finally {
    loading.value = false
  }
}

const exportStudents = async () => {
  if (exporting.value) return
  exporting.value = true
  try {
    const params = new URLSearchParams()
    if (searchQuery.value.trim()) params.append('search', searchQuery.value.trim())
    if (filters.value.status) params.append('status', filters.value.status)
    if (filters.value.payment_status) params.append('payment_status', filters.value.payment_status)
    if (filters.value.tutor_assignment) params.append('tutor_assignment', filters.value.tutor_assignment)
    if (filters.value.year) params.append('year', filters.value.year)

    const response = await axios.get(`/api/admin/students/export?${params.toString()}`, {
      responseType: 'blob'
    })

    const disposition = response.headers['content-disposition'] || ''
    const match = disposition.match(/filename="?([^"]+)"?/)
    const filename = match ? match[1] : `students-${new Date().toISOString().slice(0, 10)}.csv`

    const url = window.URL.createObjectURL(new Blob([response.data], { type: 'text/csv;charset=utf-8' }))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', filename)
    document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)

    toast.success('Export downloaded')
  } catch (error) {
    console.error('Failed to export students:', error)
    toast.error('Failed to export students')
  } finally {
    exporting.value = false
  }
}

const loadTutors = async () => {
  try {
    const response = await axios.get('/api/admin/tutors')
    if (response.data.success) {
        tutors.value = response.data.data.map(t => ({
            id: t.id,
            name: t.name || `${t.first_name} ${t.last_name}`
        }))
    }
  } catch (error) {
      console.error('Failed to load tutors', error)
  }
}

const formData = ref({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  tutorId: '',
  isActive: true
})

const filteredStudents = computed(() => {
  // Pagination is handled server-side. Exam prep access is the only client-side
  // filter (it relies on locally fetched summary), so it only narrows the
  // current page; other filters are applied server-side via fetchStudents.
  const mode = filters.value.exam_prep_access
  if (!mode) return students.value

  const total = examPrepTotalCount.value
  return students.value.filter(s => {
    const granted = examPrepAccessSummary.value[s.id] || 0
    if (mode === 'full') return total > 0 && granted === total
    if (mode === 'partial') return granted > 0 && granted < total
    if (mode === 'any') return granted > 0
    if (mode === 'none') return granted === 0
    return true
  })
})

const pagesToShow = computed(() => {
  const pages = []
  const totalPages = pagination.value.last_page
  const current = currentPage.value
  const maxPagesToShow = 5

  // Always show first page
  if (totalPages <= maxPagesToShow) {
    for (let i = 1; i <= totalPages; i++) {
      pages.push(i)
    }
  } else {
    // Show current page and surrounding pages
    let start = Math.max(1, current - 2)
    let end = Math.min(totalPages, current + 2)

    // Adjust if near start or end
    if (current <= 3) {
      end = maxPagesToShow
    } else if (current > totalPages - 3) {
      start = totalPages - maxPagesToShow + 1
    }

    // Add first page if not shown
    if (start > 1) {
      pages.push(1)
      if (start > 2) pages.push('...')
    }

    // Add page numbers
    for (let i = start; i <= end; i++) {
      pages.push(i)
    }

    // Add last page if not shown
    if (end < totalPages) {
      if (end < totalPages - 1) pages.push('...')
      pages.push(totalPages)
    }
  }

  return pages
})

const editStudent = (student) => {
  editingStudent.value = student
  formData.value = {
    firstName: student.name.split(' ')[0],
    lastName: student.name.split(' ')[1] || '',
    email: student.email,
    password: '',
    confirmPassword: '',
    tutorId: student.tutorId || '',
    isActive: student.paymentConfirmed,
    status: student.status
  }
  showAddForm.value = false
}

const savingStudent = ref(false)

const saveStudent = async () => {
  // Validate passwords for new students
  if (!editingStudent.value) {
    if (!formData.value.password || formData.value.password.length < 8) {
      toast.error('Password must be at least 8 characters')
      return
    }
    if (formData.value.password !== formData.value.confirmPassword) {
      toast.error('Passwords do not match')
      return
    }
  }

  savingStudent.value = true

  try {
    if (editingStudent.value) {
      // Update existing student
      const response = await axios.put(`/api/admin/students/${editingStudent.value.id}`, {
        first_name: formData.value.firstName,
        last_name: formData.value.lastName,
        email: formData.value.email,
        tutor_id: formData.value.tutorId || null,
        payment_confirmed: formData.value.isActive,
        status: formData.value.status
      })

      if (response.data.success) {
        toast.success('Student updated successfully')
        await loadStudents(currentPage.value)
      }
    } else {
      // Create new student
      const response = await axios.post('/api/admin/students', {
        first_name: formData.value.firstName,
        last_name: formData.value.lastName,
        email: formData.value.email,
        password: formData.value.password,
        password_confirmation: formData.value.confirmPassword,
        tutor_id: formData.value.tutorId || null,
        payment_confirmed: formData.value.isActive
      })

      if (response.data.success) {
        toast.success('Student created successfully')
        await loadStudents(currentPage.value)
      }
    }
    cancelForm()
  } catch (error) {
    console.error('Failed to save student:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save student'
    toast.error(errorMessage)
  } finally {
    savingStudent.value = false
  }
}

const assignTutor = async (studentId, event) => {
  const tutorId = event.target.value ? parseInt(event.target.value) : null
  const student = students.value.find(s => s.id === studentId)
  
  if (!student) return

  try {
    const response = await axios.post(
      `/api/admin/students/${studentId}/assign-tutor`,
      { tutor_id: tutorId }
    )
    
    if (response.data.success) {
    student.tutorId = tutorId
      toast.success(response.data.message || 'Tutor assigned successfully')
      // Reload students to get updated data
      await loadStudents()
    }
  } catch (error) {
    console.error('Failed to assign tutor:', error)
    toast.error(error.response?.data?.message || 'Failed to assign tutor')
    // Revert the select value on error
    event.target.value = student.tutorId || ''
  }
}

const togglePaymentConfirmed = async (student) => {
  if (student.togglingPayment) return

  // If we are granting access (current state is false) and student is Pending, show custom modal
  if (!student.paymentConfirmed && student.status === 'Pending') {
    studentToUpdateStatus.value = student
    showStatusConfirmModal.value = true
    return
  }

  // Otherwise proceed normally
  await proceedWithToggle(false, student)
}

const cancelToggleStatus = () => {
  showStatusConfirmModal.value = false
  studentToUpdateStatus.value = null
}

const proceedWithToggle = async (updateStatus, targetStudent = null) => {
  const student = targetStudent || studentToUpdateStatus.value
  if (!student) return

  showStatusConfirmModal.value = false
  studentToUpdateStatus.value = null
  student.togglingPayment = true

  try {
    const response = await axios.post(`/api/admin/students/${student.id}/toggle-payment`, {
      update_status: updateStatus
    })

    if (response.data.success) {
      student.paymentConfirmed = response.data.data.payment_confirmed
      if (response.data.data.status) {
        student.status = response.data.data.status
        student.isManualActivation = response.data.data.is_manual
      }
      toast.success(response.data.message)
    }
  } catch (error) {
    console.error('Failed to toggle access status:', error)
    toast.error(error.response?.data?.message || 'Failed to update access status')
  } finally {
    student.togglingPayment = false
  }
}

const changePerPage = async (newPerPage) => {
  try {
    // Save preference to database
    await axios.put(`/api/preferences/students_per_page`,
      { value: newPerPage.toString() }
    )
    perPage.value = newPerPage
    currentPage.value = 1 // Reset to first page when changing per-page
    loadStudents(1)
  } catch (error) {
    console.error('Failed to save preference:', error)
    toast.error('Failed to save preference')
  }
}

const viewStudent = (student) => {
  viewingStudent.value = student
  showViewModal.value = true
}

const closeViewModal = () => {
  showViewModal.value = false
  viewingStudent.value = null
}

const openRecords = (student) => {
  selectedStudentId.value = student.id
  selectedStudentName.value = student.name
  showRecordsModal.value = true
}

const closeRecordsModal = () => {
  showRecordsModal.value = false
  selectedStudentId.value = null
  selectedStudentName.value = ''
}

// Exam Prep Access (admin)
const examPrepModalOpen = ref(false)
const examPrepModalStudentId = ref(null)
const examPrepModalStudentName = ref('')
const examPrepList = ref([])
const examPrepLoading = ref(false)
const examPrepBulk = ref(null)
const examPrepRowSaving = ref({})
const examPrepAccessSummary = ref({})
const examPrepTotalCount = ref(0)

const examPrepGrantedCount = computed(() => examPrepList.value.filter(e => e.has_access).length)
const examPrepAllGranted = computed(() => examPrepList.value.length > 0 && examPrepGrantedCount.value === examPrepList.value.length)

const fetchExamPrepAccessSummary = async () => {
  try {
    const response = await axios.get('/api/admin/exam-prep-access-summary')
    if (response.data.success && response.data.data) {
      examPrepTotalCount.value = response.data.data.total_exam_preps || 0
      const map = {}
      for (const row of (response.data.data.students || [])) {
        map[row.student_id] = row.granted_count
      }
      examPrepAccessSummary.value = map
    }
  } catch (e) {
    // ignore
  }
}

const getGrantedCount = (studentId) => examPrepAccessSummary.value[studentId] || 0

const examPrepAccessLabel = (studentId) => {
  const granted = getGrantedCount(studentId)
  const total = examPrepTotalCount.value
  if (granted === 0) return 'Exam Prep Access'
  if (total > 0) return `Access ${granted}/${total}`
  return 'Access'
}

const openExamPrepAccess = async (student) => {
  examPrepModalStudentId.value = student.id
  examPrepModalStudentName.value = student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || student.email
  examPrepModalOpen.value = true
  examPrepList.value = []
  examPrepLoading.value = true
  try {
    const response = await axios.get(`/api/admin/students/${student.id}/exam-prep-access`)
    if (response.data.success) {
      examPrepList.value = response.data.data || []
    }
  } catch (e) {
    examPrepList.value = []
  } finally {
    examPrepLoading.value = false
  }
}

const closeExamPrepAccess = () => {
  examPrepModalOpen.value = false
  examPrepModalStudentId.value = null
  examPrepModalStudentName.value = ''
  examPrepList.value = []
  examPrepRowSaving.value = {}
}

const updateAdminSummaryForStudent = (studentId) => {
  const granted = examPrepList.value.filter(e => e.has_access).length
  examPrepAccessSummary.value = { ...examPrepAccessSummary.value, [studentId]: granted }
  if (examPrepList.value.length > examPrepTotalCount.value) {
    examPrepTotalCount.value = examPrepList.value.length
  }
}

const toggleStudentExamPrep = async (ep) => {
  if (!examPrepModalStudentId.value) return
  const studentId = examPrepModalStudentId.value
  const wasGranted = ep.has_access
  examPrepRowSaving.value = { ...examPrepRowSaving.value, [ep.id]: true }
  try {
    if (wasGranted) {
      await axios.delete(`/api/admin/exam-preps/${ep.id}/access/${studentId}`)
      ep.has_access = false
    } else {
      await axios.post(`/api/admin/exam-preps/${ep.id}/access`, { student_id: studentId })
      ep.has_access = true
    }
    updateAdminSummaryForStudent(studentId)
  } catch (e) {
    // ignore
  } finally {
    const next = { ...examPrepRowSaving.value }
    delete next[ep.id]
    examPrepRowSaving.value = next
  }
}

const grantAllExamPreps = async () => {
  if (!examPrepModalStudentId.value) return
  const studentId = examPrepModalStudentId.value
  examPrepBulk.value = 'grant'
  try {
    await axios.post(`/api/admin/students/${studentId}/exam-prep-access/grant-all`)
    examPrepList.value = examPrepList.value.map(e => ({ ...e, has_access: true }))
    updateAdminSummaryForStudent(studentId)
  } catch (e) {
    // ignore
  } finally {
    examPrepBulk.value = null
  }
}

const revokeAllExamPreps = async () => {
  if (!examPrepModalStudentId.value) return
  const studentId = examPrepModalStudentId.value
  examPrepBulk.value = 'revoke'
  try {
    await axios.delete(`/api/admin/students/${studentId}/exam-prep-access/all`)
    examPrepList.value = examPrepList.value.map(e => ({ ...e, has_access: false }))
    updateAdminSummaryForStudent(studentId)
  } catch (e) {
    // ignore
  } finally {
    examPrepBulk.value = null
  }
}

const toggleStudent = (studentId) => {
  if (selectedStudents.value.has(studentId)) {
    selectedStudents.value.delete(studentId)
  } else {
    selectedStudents.value.add(studentId)
  }
}

const toggleSelectAll = () => {
  if (selectedStudents.value.size === filteredStudents.value.length && filteredStudents.value.length > 0) {
    selectedStudents.value.clear()
  } else {
    filteredStudents.value.forEach(student => {
      selectedStudents.value.add(student.id)
    })
  }
}

const deleteStudent = async (studentId) => {
  if (!confirm('Are you sure you want to delete this student?')) return

  try {
    const response = await axios.delete(`/api/admin/students/${studentId}`)

    if (response.data.success) {
      // Reload the current page to reflect changes
      await loadStudents(currentPage.value)
      toast.success('Student deleted successfully')
    }
  } catch (error) {
    console.error('Failed to delete student:', error)
    toast.error(error.response?.data?.message || 'Failed to delete student')
  }
}

const deleteBulk = async () => {
  if (!confirm(`Are you sure you want to delete ${selectedStudents.value.size} student(s)?`)) return

  try {
    const response = await axios.post(
      '/api/admin/students/bulk-delete',
      { student_ids: Array.from(selectedStudents.value) }
    )

    if (response.data.success) {
      // Reload students from server to ensure UI is synced with database
      // If we deleted everything on the last page, go back one page
      const pageToLoad = students.value.length === selectedStudents.value.size && currentPage.value > 1
        ? currentPage.value - 1
        : currentPage.value

      await loadStudents(pageToLoad)
      selectedStudents.value.clear()
      toast.success(`Deleted ${response.data.deleted_count} student(s) successfully`)
    }
  } catch (error) {
    console.error('Failed to delete students:', error)
    toast.error(error.response?.data?.message || 'Failed to delete students')
  }
}

const cancelForm = () => {
  showAddForm.value = false
  editingStudent.value = null
  showTutorDropdown.value = false
  tutorSearchQuery.value = ''
  formData.value = {
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    confirmPassword: '',
    tutorId: '',
    isActive: true,
    status: ''
  }
}

const getInitials = (student) => {
  if (!student) return 'U'
  const name = student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || student.email || 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const loadPreference = async () => {
  try {
    const response = await axios.get(`/api/preferences/students_per_page`)
    if (response.data.data.value) {
      perPage.value = parseInt(response.data.data.value)
    }
  } catch (error) {
    console.error('Failed to load preference:', error)
    // Use default value if preference doesn't exist
    perPage.value = 15
  }
}

// Debounced search watcher
let searchTimeout = null
watch(searchQuery, (newValue) => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  searchTimeout = setTimeout(() => {
    loadStudents(1) // Reset to page 1 when searching
  }, 300) // 300ms debounce
})

const handleClickOutside = (event) => {
  // Handle form tutor dropdown
  const dropdown = document.querySelector('.tutor-dropdown-container')
  if (dropdown && !dropdown.contains(event.target)) {
    showTutorDropdown.value = false
  }

  // Handle inline tutor dropdown in table
  const inlineDropdown = document.querySelector('.inline-tutor-dropdown')
  if (openTutorDropdownId.value !== null) {
    const clickedInsideAnyInlineDropdown = event.target.closest('.inline-tutor-dropdown')
    if (!clickedInsideAnyInlineDropdown) {
      closeInlineTutorDropdown()
    }
  }
}

onMounted(async () => {
  await loadPreference()
  loadStudents()
  loadTutors()
  fetchExamPrepAccessSummary()
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
