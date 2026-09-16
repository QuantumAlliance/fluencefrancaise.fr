<template>
  <div>
    <div class="pb-0">


      <!-- Tabs Navigation -->
      <div class="border-b border-gray-200 mb-0">
        <nav class="flex space-x-8">
          <button
            @click="handleTabChange('enrollments')"
            :class="activeTab === 'enrollments' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          >
            Enrollments
          </button>
          <button
            @click="handleTabChange('coupons')"
            :class="activeTab === 'coupons' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          >
            Coupons
          </button>
          <button
            @click="handleTabChange('class-types')"
            :class="activeTab === 'class-types' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          >
            Class Types
          </button>
        </nav>
      </div>
    </div>

    <!-- Enrollments Tab Content -->
    <div v-if="activeTab === 'enrollments'" class="mt-6">

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm font-medium">Total Enrollments</p>
        <p class="text-3xl font-bold text-brand-600 mt-2">{{ stats.total }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm font-medium">Active</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.active }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm font-medium">Pending</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.pending }}</p>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm font-medium">Conversation Rate</p>
        <p class="text-3xl font-bold text-orange-600 mt-2">{{ completionRate }}%</p>
      </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search student or course..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
      />
    </div>

    <!-- Enrollments Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loading" class="p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading enrollments...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th 
              @click="handleSort('entry_id')"
              class="px-6 py-4 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 select-none"
            >
              <div class="flex items-center gap-2">
                <span>Entry ID</span>
                <span class="text-xs">{{ getSortIcon('entry_id') }}</span>
              </div>
            </th>
            <th 
              @click="handleSort('enrollment_date')"
              class="px-6 py-4 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 select-none"
            >
              <div class="flex items-center gap-2">
                <span>Enrollment Date</span>
                <span class="text-xs">{{ getSortIcon('enrollment_date') }}</span>
              </div>
            </th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Class Type</th>
            <th 
              @click="handleSort('status')"
              class="px-6 py-4 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 select-none"
            >
              <div class="flex items-center gap-2">
                <span>Status</span>
                <span class="text-xs">{{ getSortIcon('status') }}</span>
              </div>
            </th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="enrollment in filteredEnrollments" :key="enrollment.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 text-gray-700 text-sm">{{ enrollment.entryId }}</td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ enrollment.enrollmentDate }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0">
                  <span class="text-xs font-bold text-[#0055A4]">
                    {{ getInitials({ name: enrollment.studentName }) }}
                  </span>
                </div>
                <button
                  @click="viewEnrollment(enrollment.id)"
                  class="font-medium text-gray-800 hover:text-[#0055A4] hover:underline cursor-pointer transition-colors text-left"
                >
                  {{ enrollment.studentName }}
                </button>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700 text-sm">
              {{ enrollment.studentEmail || 'N/A' }}
            </td>
            <td class="px-6 py-4 text-gray-700">
              <div class="flex items-center gap-2">
                <span>{{ enrollment.classTypeName || 'N/A' }}</span>
                <span
                  v-if="enrollment.isImported"
                  class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full"
                  title="Manually Imported"
                >
                  Imported
                </span>
              </div>
            </td>
            <td class="px-6 py-4">
              <span
                v-if="enrollment.shouldShowNA"
                class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600"
              >
                N/A
              </span>
              <span
                v-else
                :class="getStatusClass(enrollment.status)"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ enrollment.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button
                @click="viewEnrollment(enrollment.id)"
                :disabled="loadingActions[`view-${enrollment.id}`]"
                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded text-sm transition-colors flex items-center gap-1"
              >
                <svg v-if="loadingActions[`view-${enrollment.id}`]" class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ loadingActions[`view-${enrollment.id}`] ? 'Loading...' : 'View' }}</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loading && filteredEnrollments.length === 0" class="p-8 text-center">
        <p class="text-gray-500">No enrollments found</p>
      </div>

      <!-- Pagination Controls -->
      <div v-if="pagination.last_page > 1 || pagination.total > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-600 flex items-center gap-4">
          <div>
            Showing <span class="font-semibold">{{ (pagination.current_page - 1) * pagination.per_page + 1 }}</span>
            to <span class="font-semibold">{{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}</span>
            of <span class="font-semibold">{{ pagination.total }}</span> enrollments
          </div>
          <div class="flex items-center gap-2">
            <label for="perPage" class="text-sm font-medium text-gray-700">Show:</label>
            <select
              id="perPage"
              :value="perPage"
              @change="changePerPage(parseInt($event.target.value))"
              class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
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
            @click="loadEnrollments(currentPage - 1)"
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
              @click="page !== '...' && loadEnrollments(page)"
              :class="[
                page === '...' ? 'cursor-default text-gray-400' : (currentPage === page ? 'bg-[#0055A4] text-white' : 'border border-gray-300 hover:bg-gray-100'),
                page === '...' ? '' : 'transition-colors'
              ]"
              class="px-3 py-2 rounded-lg text-sm font-medium"
            >
              {{ page }}
            </button>
          </div>
          <button
            @click="loadEnrollments(currentPage + 1)"
            :disabled="currentPage === pagination.last_page"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top Courses</h3>
        <div class="space-y-3">
          <div v-for="course in topCourses" :key="course.id" class="flex justify-between items-center py-2">
            <span class="text-gray-700">{{ course.name }}</span>
            <span class="font-bold text-brand-600">{{ course.enrollments }} enrolled</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Enrollment Trend</h3>
        <div class="space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-700">This Month</span>
            <span class="font-bold text-green-600">{{ enrollmentTrend.thisMonth }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-700">Last Month</span>
            <span class="font-bold text-gray-600">{{ enrollmentTrend.lastMonth }}</span>
          </div>
          <div class="flex justify-between text-sm pt-2 border-t">
            <span class="text-gray-700">Growth</span>
            <span 
              class="font-bold"
              :class="enrollmentTrend.growth >= 0 ? 'text-green-600' : 'text-red-600'"
            >
              {{ enrollmentTrend.growth >= 0 ? '+' : '' }}{{ enrollmentTrend.growth }}%
            </span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Status Distribution</h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between py-2">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-green-600 rounded-full" />
              <span class="text-gray-700">Active</span>
            </div>
            <span class="font-bold">{{ stats.active }}</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-blue-600 rounded-full" />
              <span class="text-gray-700">Completed</span>
            </div>
            <span class="font-bold">{{ stats.completed }}</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-gray-600 rounded-full" />
              <span class="text-gray-700">Pending</span>
            </div>
            <span class="font-bold">{{ stats.pending }}</span>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Coupons Tab Content -->
    <div v-if="activeTab === 'coupons'" class="mt-6">
      <Coupons :no-padding="true" />
    </div>

    <!-- Class Types Tab Content -->
    <div v-if="activeTab === 'class-types'" class="mt-6">
      <ClassTypes :no-padding="true" />
    </div>

    <!-- Enrollment Details Modal -->
    <div
      v-if="showEnrollmentModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 z-10">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Enrollment Details</h2>
            <button
              @click="closeEnrollmentModal"
              class="text-gray-500 hover:text-gray-700 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
          <div v-if="loadingEnrollmentDetails" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
            <p class="mt-2 text-gray-500">Loading enrollment details...</p>
          </div>

          <div v-else-if="selectedEnrollment" class="space-y-6">
            <!-- Student Information -->
            <div class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Student Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-600">First Name</p>
                  <p class="font-medium text-gray-900">{{ selectedEnrollment.user?.first_name || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Last Name</p>
                  <p class="font-medium text-gray-900">{{ selectedEnrollment.user?.last_name || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Email</p>
                  <p class="font-medium text-gray-900">{{ selectedEnrollment.user?.email || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Username</p>
                  <p class="font-medium text-gray-900">{{ selectedEnrollment.user?.username || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">User Type</p>
                  <p class="font-medium text-gray-900 capitalize">{{ selectedEnrollment.user?.user_type || 'N/A' }}</p>
                </div>
              </div>
            </div>

            <!-- Enrollment Information -->
            <div class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Enrollment Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-600">Course/Class Type</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.course?.course_title || selectedEnrollment.course?.title || selectedEnrollment.class_type?.class_name || selectedEnrollment.class_type?.name || 'N/A' }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Status</p>
                  <span
                    v-if="isImportedEnrollment && (!selectedEnrollment.payment || !selectedEnrollment.payment.status || selectedEnrollment.payment.status === 'pending' || selectedEnrollment.payment.status === 'processing') && (selectedEnrollment.status === 'pending' || !selectedEnrollment.status)"
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block bg-gray-100 text-gray-600"
                  >
                    N/A
                  </span>
                  <span
                    v-else-if="selectedEnrollment.status"
                    :class="getStatusClass(selectedEnrollment.status.charAt(0).toUpperCase() + selectedEnrollment.status.slice(1))"
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block"
                  >
                    {{ selectedEnrollment.status.charAt(0).toUpperCase() + selectedEnrollment.status.slice(1) }}
                  </span>
                  <span
                    v-else
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block bg-gray-100 text-gray-600"
                  >
                    N/A
                  </span>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Enrollment Date</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.enrollment_date ? new Date(selectedEnrollment.enrollment_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : (selectedEnrollment.created_at ? new Date(selectedEnrollment.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A') }}
                  </p>
                </div>
                <div v-if="selectedEnrollment.completion_date">
                  <p class="text-sm text-gray-600">Completion Date</p>
                  <p class="font-medium text-gray-900">
                    {{ new Date(selectedEnrollment.completion_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Data -->
            <div v-if="selectedEnrollment.form_data && Object.keys(selectedEnrollment.form_data).length > 0" class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Registration Form Details</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="(value, key) in filteredFormData" :key="key">
                  <p class="text-sm text-gray-600">{{ formatFormField(key) }}</p>
                  <p class="font-medium text-gray-900">
                    {{ Array.isArray(value) ? value.join(', ') : (value || 'N/A') }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Payment Information -->
            <div v-if="selectedEnrollment.payment || (selectedEnrollment.form_data && selectedEnrollment.form_data.final_amount)" class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-600">Payment ID</p>
                  <p class="font-medium text-gray-900 font-mono text-xs break-all">{{ selectedEnrollment.payment?.id || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Transaction ID (Stripe Payment Intent)</p>
                  <p class="font-medium text-gray-900 font-mono text-xs break-all">{{ selectedEnrollment.payment?.transaction_id || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Payment Status</p>
                  <span
                    v-if="selectedEnrollment.payment?.status && selectedEnrollment.payment.status !== 'pending' && selectedEnrollment.payment.status !== 'processing'"
                    :class="getPaymentStatusClass(selectedEnrollment.payment.status)"
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block"
                  >
                    {{ selectedEnrollment.payment.status.charAt(0).toUpperCase() + selectedEnrollment.payment.status.slice(1) }}
                  </span>
                  <span
                    v-else-if="isImportedEnrollment && (!selectedEnrollment.payment || !selectedEnrollment.payment.status || selectedEnrollment.payment.status === 'pending' || selectedEnrollment.payment.status === 'processing')"
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block bg-gray-100 text-gray-600"
                  >
                    N/A
                  </span>
                  <span
                    v-else
                    class="px-3 py-1 rounded-full text-sm font-medium inline-block bg-yellow-100 text-yellow-800"
                  >
                    {{ selectedEnrollment.payment?.status ? (selectedEnrollment.payment.status.charAt(0).toUpperCase() + selectedEnrollment.payment.status.slice(1)) : 'Pending' }}
                  </span>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Amount</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.payment?.amount ? new Intl.NumberFormat('en-CA', { style: 'currency', currency: selectedEnrollment.payment.currency || 'CAD' }).format(selectedEnrollment.payment.amount) : (selectedEnrollment.form_data?.final_amount ? new Intl.NumberFormat('en-CA', { style: 'currency', currency: 'CAD' }).format(selectedEnrollment.form_data.final_amount) : 'N/A') }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Paid At</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.payment?.paid_at ? new Date(selectedEnrollment.payment.paid_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A' }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Created At</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.payment?.created_at ? new Date(selectedEnrollment.payment.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'N/A' }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Additional Details -->
            <div v-if="selectedEnrollment.class_type" class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Class Type Details</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-600">Class Name</p>
                  <p class="font-medium text-gray-900">{{ selectedEnrollment.class_type.class_name || selectedEnrollment.class_type.name || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Duration</p>
                  <p class="font-medium text-gray-900 capitalize">{{ selectedEnrollment.class_type.duration || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Price</p>
                  <p class="font-medium text-gray-900">
                    {{ selectedEnrollment.class_type.price ? new Intl.NumberFormat('en-CA', { style: 'currency', currency: selectedEnrollment.class_type.currency || 'CAD' }).format(selectedEnrollment.class_type.price) : 'N/A' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
          <div class="flex justify-end">
            <button
              @click="closeEnrollmentModal"
              class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Close
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
import Coupons from './Coupons.vue'
import ClassTypes from './ClassTypes.vue'

// Tab name to hash mapping
const tabToHash = {
  'enrollments': 'enrollments',
  'coupons': 'coupons',
  'class-types': 'class-types'
}

// Hash to tab name mapping (reverse)
const hashToTab = Object.fromEntries(
  Object.entries(tabToHash).map(([tab, hash]) => [hash, tab])
)

// Get initial tab from hash or default to 'enrollments'
const getInitialTab = () => {
  const hash = window.location.hash.replace('#', '')
  return hashToTab[hash] || 'enrollments'
}

const toast = useToast()
const activeTab = ref(getInitialTab())
const loading = ref(false)

const searchQuery = ref('')
const currentPage = ref(1)

const perPage = ref(15)
const sortBy = ref('created_at')
const sortOrder = ref('desc')

const stats = ref({
  total: 0,
  active: 0,
  completed: 0,
  pending: 0
})

const enrollments = ref([])
const topCourses = ref([])
const enrollmentTrend = ref({
  thisMonth: 0,
  lastMonth: 0,
  growth: 0
})
const pagination = ref({
  current_page: 1,
  per_page: 15,
  total: 0,
  last_page: 1
})
const showEnrollmentModal = ref(false)
const selectedEnrollment = ref(null)
const loadingEnrollmentDetails = ref(false)
const loadingActions = ref({}) // Track loading state for view action per enrollment

const loadEnrollments = async (page = 1) => {
  loading.value = true
  currentPage.value = page
  try {
    const params = new URLSearchParams({
      page: page,
      per_page: perPage.value,
      sort_by: sortBy.value,
      sort_order: sortOrder.value
    })
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    const response = await axios.get(`/api/admin/enrollments?${params.toString()}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      const paginatedData = response.data.data
      const data = paginatedData.data || paginatedData || []

      // Map enrollments
      const mappedEnrollments = data.map((enrollment) => {
        // Use actual status from database, don't default to 'processing'
        // Status values: pending, active, processing, cancelled, completed
        const status = enrollment.status || 'pending'
        const statusDisplay = status.charAt(0).toUpperCase() + status.slice(1)
        
        // Check if enrollment is imported (has _imported flag in form_data)
        const formData = enrollment.form_data || {}
        const isImported = formData._imported === true || formData._imported === 'true' || formData._import_source === 'gravity_forms'
        
        // Entry ID - use entry_id column if available, otherwise fallback to form_data.entry_id or enrollment.id
        const entryId = enrollment.entry_id 
          ? enrollment.entry_id.toString()
          : (isImported && formData.entry_id 
            ? formData.entry_id.toString()
            : (enrollment.id ? enrollment.id.toString() : '-'))
        
        return {
          id: enrollment.id,
          studentName: enrollment.user ? `${enrollment.user.first_name || ''} ${enrollment.user.last_name || ''}`.trim() || enrollment.user.name : 'Unknown Student',
          studentEmail: enrollment.user?.email || 'N/A',
          studentAvatar: enrollment.user?.profile_picture,
          courseName: enrollment.course?.course_title || enrollment.course?.title || enrollment.class_type?.class_name || enrollment.class_type?.name || 'Unknown Course',
          classTypeName: enrollment.class_type?.class_name || enrollment.class_type?.name || 'N/A',
          entryId: entryId,
          enrollmentDate: enrollment.enrollment_date
            ? new Date(enrollment.enrollment_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
            : enrollment.created_at
              ? new Date(enrollment.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
              : '-',
          status: statusDisplay, // Capitalize first letter
          rawStatus: status.toLowerCase(), // Store lowercase for filtering
          userId: enrollment.user_id,
          courseId: enrollment.course_id,
          classTypeId: enrollment.class_type_id,
          formData: formData,
          enrollmentDateRaw: enrollment.enrollment_date,
          completionDate: enrollment.completion_date,
          classType: enrollment.class_type,
          course: enrollment.course,
          user: enrollment.user,
          payment: enrollment.payment, // Include payment info
          isImported: isImported, // Flag to identify imported enrollments
          shouldShowNA: isImported && (!enrollment.payment || !enrollment.payment.status || enrollment.payment.status === 'pending' || enrollment.payment.status === 'processing') && (status === 'pending' || !status) // Flag to show N/A for imported enrollments
        }
      })

      enrollments.value = mappedEnrollments

      // Store pagination metadata
      pagination.value = {
        current_page: paginatedData.current_page,
        per_page: paginatedData.per_page,
        total: paginatedData.total,
        last_page: paginatedData.last_page
      }
    }
  } catch (error) {
    toast.error('Failed to load enrollments')
    enrollments.value = []
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    const response = await axios.get('/api/admin/enrollment-stats', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      const data = response.data.data
      stats.value = {
        total: data.total || 0,
        active: data.active || 0,
        completed: data.completed || 0,
        pending: data.pending || 0,
        cancelled: data.cancelled || 0
      }
      
      // Update top courses
      if (data.top_courses && Array.isArray(data.top_courses)) {
        topCourses.value = data.top_courses.map(course => ({
          id: course.id,
          name: course.name || 'Unknown Course',
          enrollments: course.enrollments || 0
        }))
      }
      
      // Update trend
      if (data.trend) {
        enrollmentTrend.value = {
          thisMonth: data.trend.this_month || 0,
          lastMonth: data.trend.last_month || 0,
          growth: data.trend.growth || 0
        }
      }
    }
  } catch (e) {
    // Fallback: calculate stats from enrollments
    updateStatsFromEnrollments()
  }
}

const updateStatsFromEnrollments = () => {
    if (enrollments.value.length > 0) {
      stats.value.total = enrollments.value.length
      stats.value.active = enrollments.value.filter(e => e.rawStatus === 'active').length
      stats.value.completed = enrollments.value.filter(e => e.rawStatus === 'completed').length
    stats.value.pending = enrollments.value.filter(e => e.rawStatus === 'pending' || e.rawStatus === 'processing').length
    stats.value.cancelled = enrollments.value.filter(e => e.rawStatus === 'cancelled').length
  } else {
    // Reset stats if no enrollments
    stats.value = {
      total: 0,
      active: 0,
      completed: 0,
      pending: 0,
      cancelled: 0
    }
  }
}

const filteredEnrollments = computed(() => {
  // Pagination is handled server-side, so just return current page data
  return enrollments.value
})

const handleSort = async (column) => {
  // Toggle sort order if clicking the same column, otherwise set to desc
  if (sortBy.value === column) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortOrder.value = 'desc'
  }
  
  // Save preference to database
  await saveSortPreference()
  
  // Reload enrollments with new sort
  await loadEnrollments(1)
}

const saveSortPreference = async () => {
  try {
    const preferenceValue = JSON.stringify({
      sort_by: sortBy.value,
      sort_order: sortOrder.value
    })
    await axios.put(`/api/preferences/enrollments_sort`,
      { value: preferenceValue },
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
  } catch (error) {
    console.error('Failed to save sort preference:', error)
  }
}

const loadSortPreference = async () => {
  try {
    const response = await axios.get(`/api/preferences/enrollments_sort`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.data && response.data.data.value) {
      const preference = JSON.parse(response.data.data.value)
      sortBy.value = preference.sort_by || 'created_at'
      sortOrder.value = preference.sort_order || 'desc'
    }
  } catch (error) {
    console.error('Failed to load sort preference:', error)
    // Use defaults
    sortBy.value = 'created_at'
    sortOrder.value = 'desc'
  }
}

const getSortIcon = (column) => {
  if (sortBy.value !== column) {
    return '⇅' // Neutral icon
  }
  return sortOrder.value === 'asc' ? '↑' : '↓'
}

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

const completionRate = computed(() => {
  return stats.value.total > 0 ? Math.round((stats.value.completed / stats.value.total) * 100) : 0
})

const changePerPage = async (newPerPage) => {
  try {
    // Save preference to database
    await axios.put(`/api/preferences/enrollments_per_page`,
      { value: newPerPage.toString() },
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    perPage.value = newPerPage
    currentPage.value = 1 // Reset to first page when changing per-page
    loadEnrollments(1)
  } catch (error) {
    console.error('Failed to save preference:', error)
    toast.error('Failed to save preference')
  }
}

const getStatusClass = (status) => {
  const statusLower = status?.toLowerCase() || ''
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'processing': 'bg-yellow-100 text-yellow-800',
    'cancelled': 'bg-gray-100 text-gray-800',
    'completed': 'bg-blue-100 text-blue-800',
    'pending': 'bg-yellow-100 text-yellow-800'
  }
  return classes[statusLower] || 'bg-gray-100 text-gray-800'
}

const getPaymentStatusClass = (status) => {
  const statusLower = status?.toLowerCase() || ''
  const classes = {
    'completed': 'bg-green-100 text-green-800',
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'failed': 'bg-red-100 text-red-800',
    'cancelled': 'bg-gray-100 text-gray-800',
    'refunded': 'bg-[#0055A4]/10 text-[#003d7a]'
  }
  return classes[statusLower] || 'bg-gray-100 text-gray-800'
}

const viewEnrollment = async (id) => {
  loadingActions.value[`view-${id}`] = true
  loadingEnrollmentDetails.value = true
  showEnrollmentModal.value = true
  try {
    const response = await axios.get(`/api/admin/enrollments/${id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      selectedEnrollment.value = response.data.data
    }
  } catch (error) {
    toast.error('Failed to load enrollment details')
    showEnrollmentModal.value = false
  } finally {
    loadingEnrollmentDetails.value = false
    loadingActions.value[`view-${id}`] = false
  }
}

const closeEnrollmentModal = () => {
  showEnrollmentModal.value = false
  selectedEnrollment.value = null
}


const formatFormField = (key) => {
  // Map of field keys to display names
  const fieldLabels = {
    'phone': 'Phone',
    'city': 'City',
    'native_language': 'Native Language',
    'english_level': 'English Level',
    'french_level': 'French Level',
    'course_purpose': 'Course Purpose',
    'special_request': 'Special Request',
    'referral_source': 'Referral Source',
    'coupon_code': 'Coupon Code',
    'discount_amount': 'Discount Amount',
    'final_amount': 'Final Amount',
    'availability': 'Availability and Time Zone'
  }
  
  // Return mapped label or convert snake_case to Title Case
  if (fieldLabels[key]) {
    return fieldLabels[key]
  }
  
  // Fallback: Convert snake_case to Title Case
  return key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
}

// Check if selected enrollment is imported
const isImportedEnrollment = computed(() => {
  if (!selectedEnrollment.value?.form_data) return false
  const formData = selectedEnrollment.value.form_data
  return formData._imported === true || formData._imported === 'true' || formData._import_source === 'gravity_forms'
})

// Filter out Gravity Forms system/metadata fields and show only actual registration form fields
const filteredFormData = computed(() => {
  if (!selectedEnrollment.value?.form_data) return {}
  
  const formData = selectedEnrollment.value.form_data
  
  // Gravity Forms system/metadata fields to exclude
  const systemFields = [
    'Entry Id',
    'Entry Date',
    'Date Updated',
    'Source Url',
    'Transaction Id',
    'Payment Amount',
    'Payment Date',
    'Payment Status',
    'Post Id',
    'User Agent',
    'User IP',
    'Created By (User Id)'
  ]
  
  // Actual registration form fields (from Register.vue)
  // These are the fields that should be displayed
  const allowedFields = [
    'Your Name (First)',
    'Your Name (Last)',
    'Your Name (Middle)',
    'Your Name (Prefix)',
    'Your Name (Suffix)',
    'Email Address',
    'Create a Username',
    'Phone Number',
    'City',
    'Native Language',
    'English Level',
    'Current French Level',
    'Primary Purpose For Taking The Course',
    'Classes Type',
    'No Available Batches',
    'Availability (for One-on-One Sessions)',
    'Expected Course Start Date (for One-on-One)',
    'Special Request',
    'Do You Have  a Coupon Code?',
    'No Commitment. Payment can be cancelled anytime upon notifying before processing.',
    'How Did You Hear About Us?'
  ]
  
  // Filter out system fields and empty values, keep only actual form fields
  const filtered = {}
  for (const [key, value] of Object.entries(formData)) {
    // Skip system/metadata fields
    if (systemFields.includes(key)) continue
    
    // Skip import tracking fields (internal use only)
    if (key === '_imported' || key === '_import_source') continue
    
    // Skip empty values
    if (value === '' || value === null || value === undefined) continue
    
    // Only include fields that are in the actual registration form
    // OR if it's not a system field, include it (for backward compatibility)
    if (allowedFields.includes(key) || (!systemFields.includes(key) && value !== '')) {
      filtered[key] = value
    }
  }
  
  return filtered
})


const getInitials = (user) => {
  if (!user) return 'U'
  const name = user.name || `${user.first_name || ''} ${user.last_name || ''}`.trim() || user.email || 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const loadPreference = async () => {
  try {
    const response = await axios.get(`/api/preferences/enrollments_per_page`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.data.value) {
      perPage.value = parseInt(response.data.data.value)
    }
  } catch (error) {
    console.error('Failed to load preference:', error)
    // Use default value if preference doesn't exist
    perPage.value = 15
  }
}

// Handle tab change with hash update
const handleTabChange = (tab) => {
  activeTab.value = tab
  
  // Update URL hash without reloading page
  const hash = tabToHash[tab] || 'enrollments'
  window.history.pushState(null, '', `#${hash}`)
}

// Handle browser back/forward button for hash changes
const handleHashChange = () => {
  const hash = window.location.hash.replace('#', '')
  const tab = hashToTab[hash] || 'enrollments'
  if (tab !== activeTab.value) {
    activeTab.value = tab
  }
}

// Debounced search watcher
let searchTimeout = null
watch(searchQuery, (newValue) => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  searchTimeout = setTimeout(() => {
    loadEnrollments(1) // Reset to page 1 when searching
  }, 300) // 300ms debounce
})

onMounted(async () => {
  // Set initial hash if not present
  if (!window.location.hash) {
    window.history.replaceState(null, '', `#${tabToHash[activeTab.value] || 'enrollments'}`)
  }

  // Listen for hash changes (browser back/forward)
  window.addEventListener('hashchange', handleHashChange)

  await loadPreference()
  await loadSortPreference() // Load sort preferences before loading enrollments
  await Promise.all([
    loadEnrollments(),
    loadStats()
  ])
})

// Cleanup event listener on unmount
onUnmounted(() => {
  window.removeEventListener('hashchange', handleHashChange)
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
