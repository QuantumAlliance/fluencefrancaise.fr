<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Tutor Stats - {{ tutorName }}</h2>
        <button
          @click="closeModal"
          class="text-gray-500 hover:text-gray-700 transition-colors"
        >
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Tabs -->
      <div class="border-b border-gray-200">
        <div class="flex">
          <button
            @click="activeTab = 'profile'"
            :class="activeTab === 'profile' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Profile
          </button>
          <button
            @click="activeTab = 'students'"
            :class="activeTab === 'students' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Assigned Students
          </button>
          <button
            @click="activeTab = 'notes'"
            :class="activeTab === 'notes' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Notes
          </button>
          <button
            v-if="!authStore.user?.permissions?.hide_tutor_pay"
            @click="activeTab = 'pay'"
            :class="activeTab === 'pay' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Pay
          </button>
          <button
            @click="activeTab = 'tracker'"
            :class="activeTab === 'tracker' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Tracker
          </button>
          <button
            @click="activeTab = 'approvals'"
            :class="activeTab === 'approvals' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors relative"
          >
            Approvals
            <span
              v-if="timerEditPendingCount > 0"
              class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full"
            >
              {{ timerEditPendingCount }}
            </span>
          </button>
          <button
            v-if="stats.profile?.working_status === 'full_time'"
            @click="activeTab = 'vacation'"
            :class="activeTab === 'vacation' ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
            class="px-6 py-4 font-medium transition-colors"
          >
            Career
          </button>
        </div>
      </div>

      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-6">
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
          <p class="mt-2 text-gray-500">Loading tutor stats...</p>
        </div>

        <div v-else>
          <!-- Profile Tab -->
          <div v-if="activeTab === 'profile'" class="space-y-6">
            <div class="bg-gray-50 rounded-lg p-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">Profile</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-semibold text-gray-600">First Name</label>
                  <p class="text-gray-800 mt-1">{{ stats.profile?.first_name || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-600">Last Name</label>
                  <p class="text-gray-800 mt-1">{{ stats.profile?.last_name || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-600">Email</label>
                  <p class="text-gray-800 mt-1">{{ stats.profile?.email || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-600">Phone</label>
                  <p class="text-gray-800 mt-1">{{ stats.profile?.phone || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-600">Joined</label>
                  <p class="text-gray-800 mt-1">{{ formatDate(stats.profile?.created_at) }}</p>
                </div>
                <div v-if="stats.profile?.biography" class="md:col-span-2">
                  <label class="text-sm font-semibold text-gray-600">Biography</label>
                  <p class="text-gray-800 mt-1">{{ stats.profile.biography }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Assigned Students Tab -->
          <div v-if="activeTab === 'students'" class="space-y-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-gray-800">Assigned Students ({{ stats.students_count || 0 }})</h3>
            </div>
            <div v-if="stats.assigned_students && stats.assigned_students.length > 0" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="student in stats.assigned_students" :key="student.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0">
                          <span class="text-xs font-bold text-[#0055A4]">
                            {{ getInitials(student) }}
                          </span>
                        </div>
                        <span class="font-medium text-gray-800">{{ student.name }}</span>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ student.email }}</td>
                    <td class="px-4 py-3 text-gray-700 text-sm">{{ formatDate(student.created_at) }}</td>
                    <td class="px-4 py-3">
                      <div class="flex items-center gap-2">
                        <button
                          @click="openStudentRecords(student)"
                          class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white rounded text-sm transition-colors"
                        >
                          Records
                        </button>
                        <button
                          @click="removeStudent(student)"
                          class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors"
                          title="Remove student from this tutor"
                        >
                          Remove
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
              No students assigned
            </div>
          </div>

          <!-- Notes Tab -->
          <div v-if="activeTab === 'notes'" class="space-y-6">
            <!-- Add Note Form -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="text-lg font-semibold text-gray-700 mb-4">Add New Note</h3>
              <form @submit.prevent="saveNote" class="space-y-4">
                <div>
                  <label class="block text-gray-700 text-sm font-semibold mb-2">Date</label>
                  <input
                    v-model="noteForm.date"
                    type="date"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  />
                </div>

                <div>
                  <label class="block text-gray-700 text-sm font-semibold mb-2">Note</label>
                  <textarea
                    v-model="noteForm.note"
                    rows="3"
                    required
                    placeholder="Enter your note..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  ></textarea>
                </div>

                <div class="flex gap-3">
                  <button
                    type="submit"
                    :disabled="isSavingNote"
                    class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ isSavingNote ? 'Saving...' : 'Save Note' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- Existing Notes -->
            <div v-if="tutorNotes.length > 0" class="space-y-3">
              <h3 class="text-lg font-semibold text-gray-700 mb-3">Previous Notes</h3>
              <div v-for="note in tutorNotes" :key="note.id" class="bg-white border border-gray-200 p-4 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                      <span class="text-sm font-semibold text-[#0055A4]">{{ note.note_date }}</span>
                      <span class="text-xs text-gray-500">by {{ note.admin_name }}</span>
                    </div>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ note.note }}</p>
                  </div>
                  <button
                    @click="deleteNote(note.id)"
                    class="ml-3 px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="text-center text-gray-500 py-4">
              No notes yet
            </div>
          </div>

          <!-- Pay Tab -->
          <div v-if="activeTab === 'pay' && !authStore.user?.permissions?.hide_tutor_pay" class="space-y-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-gray-800">Tutor Pay Rates (Per Student Count)</h3>
            </div>
            <div class="grid grid-cols-1 gap-4">
              <div v-for="n in 5" :key="n" 
                   class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl transition-shadow">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-full bg-[#0055A4]/10 flex items-center justify-center border border-[#0055A4]/20">
                    <span class="text-sm font-bold text-[#0055A4]">{{ n }}</span>
                  </div>
                  <div>
                    <p class="font-bold text-gray-800">{{ n }} {{ n === 1 ? 'Student' : 'Students' }}</p>
                    <p class="text-xs text-gray-500">Rate for managing {{ n }} {{ n === 1 ? 'student' : 'students' }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <div class="relative flex items-center gap-2">
                    <span v-if="localSavingTier === n" class="text-[10px] text-[#0055A4] animate-pulse absolute -left-12">Saving...</span>
                    <div class="relative">
                      <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                      <input 
                        type="number" 
                        v-model="tierRates[n]"
                        @input="debounceSave(n)"
                        class="w-36 pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all text-right font-semibold text-gray-700"
                        placeholder="0.00"
                        step="0.01"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tracker Tab -->
          <div v-if="activeTab === 'tracker'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Left: Google API Tracker -->
              <div class="bg-white border border-gray-100 rounded-2xl p-6 flex flex-col min-h-[450px]">
                <div class="flex items-center justify-between mb-5">
                  <h3 class="text-sm font-semibold text-gray-900">Google API Tracker</h3>
                  <span class="flex items-center gap-1.5 text-[11px] text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Live
                  </span>
                </div>

                <div>
                  <flat-pickr
                    v-model="leftDateRange"
                    :config="flatpickrConfig"
                    placeholder="Select date range"
                    class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all text-gray-700"
                  />
                  <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 px-0.5">
                    <button
                      v-for="preset in quickRangePresets"
                      :key="`left-${preset.key}`"
                      type="button"
                      @click="applyQuickRange('left', preset.key)"
                      :class="isActiveQuickRange(leftDateRange, preset.key)
                        ? 'text-[#0055A4]'
                        : 'text-gray-400 hover:text-gray-700'"
                      class="text-[11px] font-medium transition-colors"
                    >
                      {{ preset.label }}
                    </button>
                  </div>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center py-10">
                  <div v-if="loadingHours" class="h-14 w-32 bg-gray-100 animate-pulse rounded"></div>
                  <div v-else class="text-center">
                    <p class="text-6xl font-semibold text-gray-900 tracking-tight leading-none">
                      {{ staffDuration.formatted_duration || '0' }}
                    </p>
                    <p class="mt-4 text-[11px] text-gray-400">
                      {{ stats.profile?.email || 'Tutor' }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Right: Manual Time Logs -->
              <div class="bg-white border border-gray-100 rounded-2xl flex flex-col min-h-[450px] overflow-hidden">
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                  <div class="flex items-center justify-between gap-4 mb-3">
                    <h3 class="text-sm font-semibold text-gray-900">Manual Time Logs</h3>
                    <div class="max-w-[180px] flex-1">
                      <flat-pickr
                        v-model="rightDateRange"
                        :config="flatpickrConfig"
                        placeholder="All time"
                        class="w-full px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] outline-none transition-all text-gray-700"
                      />
                    </div>
                  </div>
                  <div class="flex flex-wrap items-center justify-end gap-x-4 gap-y-1">
                    <button
                      v-for="preset in quickRangePresets"
                      :key="`right-${preset.key}`"
                      type="button"
                      @click="applyQuickRange('right', preset.key)"
                      :class="isActiveQuickRange(rightDateRange, preset.key)
                        ? 'text-[#0055A4]'
                        : 'text-gray-400 hover:text-gray-700'"
                      class="text-[11px] font-medium transition-colors"
                    >
                      {{ preset.label }}
                    </button>
                  </div>
                </div>

                <div class="px-6 py-5 flex-1 overflow-y-auto">
                  <div class="flex items-center justify-between mb-2 text-[10px] font-medium text-gray-400 uppercase tracking-wider">
                    <span>Tier</span>
                    <span>Accumulated</span>
                  </div>
                  <div class="divide-y divide-gray-50">
                    <div v-for="i in 5" :key="i" class="flex items-center justify-between py-3.5">
                      <div class="flex items-center gap-3">
                        <span :class="tieredRecords[i] > 0 ? 'text-gray-700' : 'text-gray-300'"
                              class="w-4 text-center text-sm font-semibold tabular-nums">{{ i }}</span>
                        <div>
                          <p class="text-base font-semibold text-gray-900 tracking-tight">{{ i }}-1 Session</p>
                          <p class="text-xs text-gray-500 font-medium mt-0.5">{{ i === 1 ? 'Solo' : i + ' students' }}</p>
                        </div>
                      </div>
                      <span :class="tieredRecords[i] > 0 ? 'text-gray-900 font-bold' : 'text-gray-300 font-semibold'"
                            class="text-base tabular-nums">
                        {{ formatDurationShort(tieredRecords[i] || 0) }}
                      </span>
                    </div>
                  </div>

                  <div class="mt-8">
                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-2">Pay breakdown</p>
                    <div v-if="payBreakdown.tiers.length > 0" class="divide-y divide-gray-50">
                      <div v-for="tier in payBreakdown.tiers" :key="tier.label"
                           class="flex items-center justify-between py-3">
                        <div>
                          <p class="text-sm text-gray-800">{{ tier.label }}</p>
                          <p class="text-[10px] text-gray-400 mt-0.5">{{ tier.formula }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900 tabular-nums">${{ tier.cost }}</p>
                      </div>
                    </div>
                    <p v-else class="text-center py-8 text-xs text-gray-400">
                      No billable sessions tracked
                    </p>
                  </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                  <span class="text-xs text-gray-500">Estimated payout</span>
                  <span class="text-lg font-semibold text-gray-900 tabular-nums">${{ payBreakdown.total }}</span>
                </div>
              </div>
            </div>
          </div>

            <!-- Approvals Tab (Timer Edit Requests) -->
            <div v-if="activeTab === 'approvals'" class="space-y-6">

              <!-- Summary bar -->
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Timer Edit Requests</h3>
                <div class="flex items-center gap-3">
                  <button
                    v-if="timerEditPendingCount > 1"
                    @click="approveAllTimerEdits"
                    class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5 shadow-sm flex items-center gap-2"
                  >
                    <i class="fas fa-check-double text-[10px]"></i>
                    Approve All ({{ timerEditPendingCount }})
                  </button>
                  <div class="px-3 py-1.5 rounded-xl text-xs font-bold tracking-tight border"
                       :class="timerEditPendingCount > 0 ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'">
                    {{ timerEditPendingCount }} Pending
                  </div>
                </div>
              </div>

              <!-- Grouped by Student → Date -->
              <div v-if="groupedTimerEdits.length > 0" class="space-y-5">
                <div v-for="studentGroup in groupedTimerEdits" :key="studentGroup.student_name" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                  <!-- Student Header -->
                  <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 rounded-xl bg-[#0055A4]/10 flex items-center justify-center border border-[#0055A4]/20">
                        <span class="text-xs font-bold text-[#0055A4]">{{ getInitials({ name: studentGroup.student_name }) }}</span>
                      </div>
                      <div>
                        <p class="font-bold text-gray-900 text-sm">{{ studentGroup.student_name }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">{{ studentGroup.totalPending }} pending edit{{ studentGroup.totalPending !== 1 ? 's' : '' }}</p>
                      </div>
                    </div>
                    <div v-if="studentGroup.totalPending > 1" class="flex items-center gap-2">
                      <button
                        @click="approveStudentTimerEdits(studentGroup.pendingIds)"
                        class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold transition-all"
                      >
                        Approve All
                      </button>
                      <button
                        @click="rejectStudentTimerEdits(studentGroup.pendingIds)"
                        class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-lg text-[10px] font-bold transition-all"
                      >
                        Reject All
                      </button>
                    </div>
                  </div>

                  <!-- Date groups within student -->
                  <div class="divide-y divide-gray-50">
                    <div v-for="dateGroup in studentGroup.dates" :key="dateGroup.date" class="px-6 py-3">

                      <!-- Date label row -->
                      <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ formatTimerEditDateShort(dateGroup.date) }}</span>
                        <div class="h-px flex-1 bg-gray-100"></div>
                        <span class="text-[10px] text-gray-300">{{ dateGroup.requests.length }} record{{ dateGroup.requests.length !== 1 ? 's' : '' }}</span>
                      </div>

                      <!-- Individual records within this date -->
                      <div class="space-y-2">
                        <div v-for="req in dateGroup.requests" :key="req.id"
                             class="flex items-center justify-between py-2 px-3 rounded-xl transition-all"
                             :class="{
                               'bg-amber-50/50 hover:bg-amber-50': req.status === 'pending',
                               'bg-emerald-50/30': req.status === 'approved',
                               'bg-gray-50/50': req.status === 'rejected'
                             }">
                          <div class="flex items-center gap-4">
                            <!-- Timer change visualization -->
                            <div class="flex items-center gap-2">
                              <span class="font-mono text-xs font-bold px-2 py-1 rounded-md bg-red-50 text-red-500 border border-red-100 min-w-[48px] text-center">{{ req.old_timer || '—' }}</span>
                              <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                              </svg>
                              <span class="font-mono text-xs font-bold px-2 py-1 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 min-w-[48px] text-center">{{ req.new_timer }}</span>
                            </div>

                            <!-- Status badge -->
                            <span
                              :class="{
                                'text-amber-600 bg-amber-100 border-amber-200': req.status === 'pending',
                                'text-emerald-600 bg-emerald-100 border-emerald-200': req.status === 'approved',
                                'text-red-500 bg-red-100 border-red-200': req.status === 'rejected'
                              }"
                              class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest border"
                            >
                              {{ req.status }}
                            </span>

                            <!-- Submitted time -->
                            <span class="text-[10px] text-gray-300 font-medium hidden lg:inline">{{ formatTimerEditDate(req.created_at) }}</span>
                          </div>

                          <!-- Actions -->
                          <div class="flex items-center gap-1.5 flex-shrink-0">
                            <template v-if="req.status === 'pending'">
                              <button @click="approveTimerEdit(req.id)" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold transition-all hover:-translate-y-0.5 shadow-sm">
                                <i class="fas fa-check mr-1"></i>Approve
                              </button>
                              <button @click="rejectTimerEdit(req.id)" class="px-3 py-1.5 bg-white hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-lg text-[10px] font-bold transition-all border border-gray-200 hover:border-red-200">
                                <i class="fas fa-times mr-1"></i>Reject
                              </button>
                            </template>
                            <template v-else>
                              <span v-if="req.admin_notes" class="text-[10px] text-gray-400 italic truncate max-w-[150px]" :title="req.admin_notes">{{ req.admin_notes }}</span>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty state -->
              <div v-else class="text-center py-16">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-100">
                  <i class="fas fa-check-circle text-gray-200 text-2xl"></i>
                </div>
                <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">No timer edit requests</p>
                <p class="text-xs text-gray-300 mt-1">All caught up!</p>
              </div>
            </div>

            <!-- Vacation/Career Section (Full-time only) -->
            <div v-if="activeTab === 'vacation' && stats.profile?.working_status === 'full_time'" class="space-y-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-white">
                <div>
                  <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider">Career History</h3>
                  <p class="text-sm text-gray-400 font-medium mt-0.5">Manage pending and approved dates</p>
                </div>
                <div class="flex items-center gap-6">
                  <div class="flex items-center gap-3 px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl transition-all hover:border-gray-200">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Max Allowance:</span>
                    <div class="flex items-center gap-2">
                      <input 
                        type="number" 
                        v-model="vacationSettings.maxDays"
                        @input="debounceVacationSettings"
                        class="w-16 bg-white border border-gray-200 rounded-lg py-1 px-2 text-sm font-bold text-gray-900 outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] text-center transition-all"
                      >
                      <div v-if="savingVacationSettings" class="w-4 h-4 border-2 border-[#0055A4] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                  </div>
                  <div class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold tracking-tight border border-emerald-100">
                    {{ vacationData.vacations?.length || 0 }} RECORDS
                  </div>
                </div>
              </div>

              <!-- Quota Summary for Admin -->
              <div class="px-8 py-4 bg-white border-b border-gray-50 flex items-center gap-12">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                  <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block leading-none mb-1">Total Used</span>
                    <span class="text-xl font-bold text-gray-900">{{ vacationData.used_days || 0 }} Days</span>
                  </div>
                </div>
                <div class="flex items-center gap-3 border-l border-gray-100 pl-12">
                  <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                  <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block leading-none mb-1">Available Remaining</span>
                    <span class="text-xl font-bold text-emerald-600">{{ vacationData.remaining_days || 0 }} Days</span>
                  </div>
                </div>
              </div>

              <div class="p-6">
                <div v-if="vacationData.vacations && vacationData.vacations.length > 0" class="space-y-4">
                  <div v-for="vacation in vacationData.vacations" :key="vacation.id"
                       class="flex items-center justify-between p-4 rounded-2xl hover:bg-gray-50/50 transition-all group border border-gray-50 hover:border-emerald-100">
                    <div class="flex items-center gap-6">
                      <div class="w-10 h-10 bg-white shadow-sm border border-gray-100 rounded-xl flex items-center justify-center text-lg">
                        ☕
                      </div>
                      <div>
                        <div class="flex flex-col gap-1">
                          <template v-if="vacation.status === 'pending'">
                            <div class="flex items-center gap-2">
                              <flat-pickr 
                                v-model="vacation.start_date"
                                :config="{ dateFormat: 'Y-m-d' }"
                                class="text-xs font-bold border-b border-gray-200 py-0.5 w-24 bg-transparent outline-none focus:border-emerald-500 transition-colors"
                              />
                              <span class="text-gray-300 font-bold">→</span>
                              <flat-pickr 
                                v-model="vacation.end_date"
                                :config="{ dateFormat: 'Y-m-d' }"
                                class="text-xs font-bold border-b border-gray-200 py-0.5 w-24 bg-transparent outline-none focus:border-emerald-500 transition-colors"
                              />
                              <span class="ml-2 text-[9px] font-black text-amber-500 uppercase tracking-widest px-2 py-0.5 bg-amber-50 rounded-full">
                                {{ vacation.status }}
                              </span>
                            </div>
                          </template>
                          <template v-else>
                            <div class="flex items-center gap-3">
                              <p class="font-bold text-gray-800 text-sm tracking-tight">{{ formatVacationDate(vacation.start_date) }} — {{ formatVacationDate(vacation.end_date) }}</p>
                              <span 
                                :class="vacation.status === 'approved' ? 'text-emerald-500 bg-emerald-50' : 'text-red-500 bg-red-50'"
                                class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest"
                              >
                                {{ vacation.status }}
                              </span>
                            </div>
                          </template>
                        </div>
                        <div class="flex items-center gap-2 text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">
                          <span>{{ vacation.total_days }} days</span>
                          <span v-if="vacation.reason" class="normal-case italic font-medium text-gray-300 truncate max-w-[200px]">"{{ vacation.reason }}"</span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                      <template v-if="vacation.status === 'pending'">
                        <button @click="approveVacation(vacation)" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm transition-all hover:-translate-y-0.5">
                          Approve
                        </button>
                        <button @click="rejectVacation(vacation.id)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                          Reject
                        </button>
                      </template>
                      <button @click="deleteAdminVacation(vacation.id)" class="p-2 text-gray-200 hover:text-red-500 transition-all opacity-0 group-hover:opacity-100">
                        <i class="fas fa-trash-alt text-xs"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-12">
                  <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">No vacation history found</p>
                </div>
              </div>
            </div>

            <!-- Visual Calendar - Clean & Proportional -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
              <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Interactive Calendar</h3>
                <div class="flex items-center gap-1">
                  <button @click="prevMonth" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
                    <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                  </button>
                  <span class="text-xs font-bold text-gray-600 uppercase tracking-widest w-40 text-center">{{ calendarMonthLabel }}</span>
                  <button @click="nextMonth" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                  </button>
                </div>
              </div>

              <div class="p-8">
                <div class="grid grid-cols-7 gap-1 mb-4">
                  <div v-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day"
                       class="text-center text-xs font-black text-gray-300 uppercase py-2">{{ day }}</div>
                </div>
                <div class="grid grid-cols-7 gap-1">
                  <div v-for="(day, idx) in calendarDays" :key="idx"
                       class="aspect-square flex flex-col items-center justify-center rounded-xl text-sm relative transition-all duration-300"
                       :class="getDayClass(day)">
                    <span v-if="day.date" class="relative z-10 font-bold opacity-30 group-hover:opacity-100">{{ day.dayNum }}</span>
                    <span v-if="day.isVacation" class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200 mt-1"></span>
                  </div>
                </div>

                <div class="flex items-center gap-8 mt-10 justify-center">
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Dates Booked</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-100 border border-blue-200 shadow-sm shadow-blue-50"></div>
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Today</span>
                  </div>
                </div>
              </div>
            </div>
            </div> <!-- end vacation/career wrapper -->
          </div>
        </div>

        <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
        <button
          @click="closeModal"
          class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
        >
          Close
        </button>
      </div>
    </div>

    <!-- Student Records Modal -->
    <StudentRecordsModal
      :show="showStudentRecordsModal"
      :student-id="selectedStudentId"
      :student-name="selectedStudentName"
      @close="closeStudentRecordsModal"
    />
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import axios from 'axios'
import { useToast } from '../composables/useToast'
import { useAuthStore } from '../stores/auth'
import StudentRecordsModal from './StudentRecordsModal.vue'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

const authStore = useAuthStore()
const toast = useToast()
const props = defineProps({
  show: Boolean,
  tutorId: Number,
  tutorName: String
})

const emit = defineEmits(['close'])

const activeTab = ref('profile')
const loading = ref(false)
const stats = ref({
  profile: null,
  assigned_students: [],
  students_count: 0,
  records: [],
  groups: []
})
const showStudentRecordsModal = ref(false)
const selectedStudentId = ref(null)
const selectedStudentName = ref('')
const tierRates = ref({})
const localSavingTier = ref(null)

// Notes state
const tutorNotes = ref([])
const isSavingNote = ref(false)
const noteForm = ref({
  date: new Date().toISOString().split('T')[0],
  note: ''
})
let saveTimeout = null

const staffDuration = ref({})
const loadingHours = ref(false)

const leftDateRange = ref('')
const rightDateRange = ref('')

const flatpickrConfig = {
  mode: 'range',
  dateFormat: 'Y-m-d',
  allowInput: true,
}

// Timer edit requests state
const timerEditRequests = ref([])
const timerEditPendingCount = ref(0)

// Vacation state
const vacationData = ref({ vacations: [], used_days: 0, max_days: 0 })
const vacationSettings = ref({ maxDays: 0 })
const savingVacationSettings = ref(false)
const calendarMonth = ref(new Date().getMonth())
const calendarYear = ref(new Date().getFullYear())
let vacationSettingsTimeout = null

const parseRange = (rangeStr) => {
  if (!rangeStr) return { start: null, end: null }
  const parts = rangeStr.split(' to ')
  return {
    start: parts[0] || null,
    end: parts[1] || parts[0] || null
  }
}

const formatYmd = (d) => {
  const y = d.getFullYear()
  const m = String(d.getMonth() + 1).padStart(2, '0')
  const day = String(d.getDate()).padStart(2, '0')
  return `${y}-${m}-${day}`
}

// Build a "YYYY-MM-DD to YYYY-MM-DD" range string matching flatpickr's range format
const buildQuickRange = (preset) => {
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  if (preset === 'all') return ''
  if (preset === 'today') return `${formatYmd(today)} to ${formatYmd(today)}`

  if (preset === 'week') {
    // ISO week: Monday → Sunday
    const day = today.getDay() // 0=Sun .. 6=Sat
    const offsetToMonday = day === 0 ? -6 : 1 - day
    const monday = new Date(today)
    monday.setDate(today.getDate() + offsetToMonday)
    const sunday = new Date(monday)
    sunday.setDate(monday.getDate() + 6)
    return `${formatYmd(monday)} to ${formatYmd(sunday)}`
  }

  if (preset === 'month') {
    const first = new Date(today.getFullYear(), today.getMonth(), 1)
    const last = new Date(today.getFullYear(), today.getMonth() + 1, 0)
    return `${formatYmd(first)} to ${formatYmd(last)}`
  }

  return ''
}

const quickRangePresets = [
  { key: 'today', label: 'Today' },
  { key: 'week', label: 'This Week' },
  { key: 'month', label: 'This Month' },
  { key: 'all', label: 'All Time' },
]

const isActiveQuickRange = (currentValue, preset) => {
  return (currentValue || '') === buildQuickRange(preset)
}

const applyQuickRange = (target, preset) => {
  const value = buildQuickRange(preset)
  if (target === 'left') leftDateRange.value = value
  else if (target === 'right') rightDateRange.value = value
}

const debounceSave = (tier) => {
    localSavingTier.value = tier
    if (saveTimeout) clearTimeout(saveTimeout)
    saveTimeout = setTimeout(() => {
        saveRates()
    }, 1000)
}

const saveRates = async () => {
    try {
        await axios.post(`/api/admin/tutors/${props.tutorId}/pay-rates`, {
            pay_rates: tierRates.value
        }, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        localSavingTier.value = null
    } catch (error) {
        console.error('Failed to save pay rates:', error)
        toast.error('Failed to auto-save pay rates')
        localSavingTier.value = null
    }
}

const loadInstructorHours = async () => {
  if (!stats.value.profile?.email) return

  loadingHours.value = true
  try {
    const { start, end } = parseRange(leftDateRange.value)
    const response = await axios.post('/api/admin/meet-logs/staff-duration', {
      email: stats.value.profile.email,
      start_date: start,
      end_date: end
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      staffDuration.value = response.data.data
    }
  } catch (error) {
    console.error('Error calculating staff duration:', error)
  } finally {
    loadingHours.value = false
  }
}

const loadStats = async () => {
  if (!props.tutorId) return

  loading.value = true
  try {
    const response = await axios.get(`/api/admin/tutors/${props.tutorId}/stats`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      stats.value = {
        profile: response.data.data.profile || null,
        assigned_students: response.data.data.assigned_students || [],
        students_count: response.data.data.students_count || 0,
        records: response.data.data.records || [],
        groups: response.data.data.groups || []
      }
      // Populate tierRates from saved data
      if (response.data.data.pay_rates) {
          tierRates.value = response.data.data.pay_rates
      } else {
          // Initialize empty object
          tierRates.value = {}
      }
    } else {
      toast.error('Failed to load tutor stats')
    }
  } catch (error) {
    console.error('Failed to load tutor stats:', error)
    console.error('Error response:', error.response?.data)
    const errorMessage = error.response?.data?.message || error.message || 'Failed to load tutor stats'
    toast.error(errorMessage)
  } finally {
    loading.value = false
  }
}

const removeStudent = async (student) => {
  if (!confirm(`Are you sure you want to remove ${student.name} from this tutor?`)) return

  try {
    const response = await axios.post(`/api/admin/tutors/${props.tutorId}/remove-student`, {
      student_id: student.id
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      toast.success('Student removed successfully')
      loadStats() // Reload to refresh the list
    } else {
      toast.error(response.data.message || 'Failed to remove student')
    }
  } catch (error) {
    console.error('Failed to remove student:', error)
    const errorMessage = error.response?.data?.message || 'Failed to remove student'
    toast.error(errorMessage)
  }
}

const closeModal = () => {
  emit('close')
  tutorNotes.value = []
  noteForm.value = {
    date: new Date().toISOString().split('T')[0],
    note: ''
  }
}

const loadNotes = async () => {
  if (!props.tutorId) return
  try {
    const response = await axios.get(`/api/admin/tutors/${props.tutorId}/notes`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      tutorNotes.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading notes:', error)
    toast.error('Failed to load notes')
  }
}

const saveNote = async () => {
  if (!noteForm.value.date || !noteForm.value.note) {
    toast.error('Please fill in all fields')
    return
  }

  isSavingNote.value = true
  try {
    const response = await axios.post(
      `/api/admin/tutors/${props.tutorId}/notes`,
      {
        note_date: noteForm.value.date,
        note: noteForm.value.note
      },
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    if (response.data.success) {
      toast.success('Note saved successfully')
      noteForm.value = {
        date: new Date().toISOString().split('T')[0],
        note: ''
      }
      await loadNotes()
    }
  } catch (error) {
    console.error('Error saving note:', error)
    toast.error('Failed to save note')
  } finally {
    isSavingNote.value = false
  }
}

const deleteNote = async (noteId) => {
  if (!confirm('Are you sure you want to delete this note?')) return
  try {
    const response = await axios.delete(
      `/api/admin/tutors/${props.tutorId}/notes/${noteId}`,
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    if (response.data.success) {
      toast.success('Note deleted successfully')
      await loadNotes()
    }
  } catch (error) {
    console.error('Error deleting note:', error)
    toast.error('Failed to delete note')
  }
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  } catch (e) {
    return 'N/A'
  }
}

const getInitials = (student) => {
  if (!student) return 'U'
  const name = (student.name || student.email || 'U').toString()
  if (!name || typeof name !== 'string') return 'U'
  const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
  if (parts.length === 0) return 'U'
  if (parts.length === 1) return parts[0].charAt(0).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const openStudentRecords = (student) => {
  selectedStudentId.value = student.id
  selectedStudentName.value = student.name || 'Student'
  showStudentRecordsModal.value = true
}

const closeStudentRecordsModal = () => {
  showStudentRecordsModal.value = false
  selectedStudentId.value = null
  selectedStudentName.value = ''
}

const sortRecordsDesc = (records) => {
  if (!records || !Array.isArray(records)) return []
  return [...records].sort((a, b) => new Date(b.date) - new Date(a.date))
}

const parseTimerToSeconds = (timerStr) => {
  if (!timerStr || typeof timerStr !== 'string') return 0
  let totalSeconds = 0
  const hours = timerStr.match(/(\d+)h/)
  const minutes = timerStr.match(/(\d+)m/)
  const seconds = timerStr.match(/(\d+)s/)
  
  if (hours) totalSeconds += parseInt(hours[1]) * 3600
  if (minutes) totalSeconds += parseInt(minutes[1]) * 60
  if (seconds) totalSeconds += parseInt(seconds[1])
  
  return totalSeconds
}

const filteredRecords = computed(() => {
  if (!stats.value.records || !Array.isArray(stats.value.records)) return []
  const { start, end } = parseRange(rightDateRange.value)
  if (!start) return stats.value.records

  const startDate = new Date(start)
  startDate.setHours(0, 0, 0, 0)
  const endDate = new Date(end || start)
  endDate.setHours(23, 59, 59, 999)

  return stats.value.records.map(studentGroup => {
    if (!studentGroup.records) return { ...studentGroup, records: [] }
    
    const filteredGroupRecords = studentGroup.records.filter(record => {
      const recordDate = new Date(record.date)
      recordDate.setHours(0, 0, 0, 0)
      return recordDate >= startDate && recordDate <= endDate
    })
    return { ...studentGroup, records: filteredGroupRecords }
  }).filter(group => group.records.length > 0)
})

const totalStartStopDuration = computed(() => {
  if (!filteredRecords.value || !Array.isArray(filteredRecords.value)) return '0m'

  let totalSeconds = 0
  filteredRecords.value.forEach(studentGroup => {
    if (studentGroup.records && Array.isArray(studentGroup.records)) {
      studentGroup.records.forEach(record => {
        const attendance = record.attendance || 'Present'

        // Absent with notice: 0 hours
        if (attendance === 'Absent (Notice Given)') return

        // Missed without notice (individual, no group): count as 1 hour
        if (attendance === 'Missed (No Notice)' && !record.group_size) {
          totalSeconds += 3600
          return
        }

        // Present OR group session (including absent in group): count actual timer
        if (record.timer) {
          totalSeconds += parseTimerToSeconds(record.timer)
        }
      })
    }
  })

  const h = Math.floor(totalSeconds / 3600)
  const m = Math.floor((totalSeconds % 3600) / 60)
  const s = totalSeconds % 60

  const parts = []
  if (h > 0) parts.push(`${h}h`)
  if (m > 0) parts.push(`${m}m`)
  if (s > 0 || parts.length === 0) parts.push(`${s}s`)

  return parts.join(' ')
})

const tieredRecords = computed(() => {
  if (!filteredRecords.value) return {}

  const sessions = {}
  // Track individual "Missed (No Notice)" records separately (1hr at tier 1)
  let missedNoNoticeSeconds = 0

  filteredRecords.value.forEach(studentGroup => {
    if (studentGroup.records) {
      studentGroup.records.forEach(record => {
        const attendance = record.attendance || 'Present'

        // Skip "Absent (Notice Given)" — 0 hours, no pay
        if (attendance === 'Absent (Notice Given)') return

        // "Missed (No Notice)" without group_size = individual session → 1hr at tier 1
        if (attendance === 'Missed (No Notice)' && !record.group_size) {
          missedNoNoticeSeconds += 3600
          return
        }

        if (record.timer && record.timer.trim() !== '' && record.timer !== 'No timer') {
          const sessionKey = `${record.date}_${record.timer}_${record.notes || ''}`
          if (!sessions[sessionKey]) {
            sessions[sessionKey] = {
              studentCount: 0,
              groupSize: record.group_size || 0,
              seconds: parseTimerToSeconds(record.timer)
            }
          }
          // For group sessions, use enrolled group_size; track max seen
          if (record.group_size && record.group_size > sessions[sessionKey].groupSize) {
            sessions[sessionKey].groupSize = record.group_size
          }
          sessions[sessionKey].studentCount++
        }
      })
    }
  })

  const aggregates = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }

  // Add individual "Missed (No Notice)" hours at tier 1
  aggregates[1] += missedNoNoticeSeconds

  Object.values(sessions).forEach(session => {
    // Use group_size (enrolled count) for tier if available, else use actual student count
    const tier = Math.min(session.groupSize || session.studentCount, 5)
    if (tier > 0) {
      aggregates[tier] += session.seconds
    }
  })

  return aggregates
})

const payBreakdown = computed(() => {
  let totalCost = 0
  const tiersBreakdown = []
  
  for (let i = 1; i <= 5; i++) {
    const seconds = tieredRecords.value[i] || 0
    if (seconds > 0) {
      const rate = parseFloat(tierRates.value[i] || 0)
      const hours = seconds / 3600
      const cost = hours * rate
      
      totalCost += cost
      tiersBreakdown.push({
        label: `${i}-1`,
        duration: formatDurationShort(seconds),
        rate: rate.toFixed(2),
        cost: cost.toFixed(2),
        formula: `(${seconds}s ÷ 3600) × $${rate.toFixed(2)}/hr`
      })
    }
  }
  
  return {
    tiers: tiersBreakdown,
    total: totalCost.toFixed(2)
  }
})

const groupStats = computed(() => {
  if (!filteredRecords.value) return []
  
  const results = []
  const groupedStudentIds = new Set()

  // 1. Process explicit groups
  if (stats.value.groups && Array.isArray(stats.value.groups)) {
    stats.value.groups.forEach(group => {
      let totalSeconds = 0
      const processedSessions = new Set() 

      if (group.student_ids && Array.isArray(group.student_ids)) {
        group.student_ids.forEach(studentId => {
          groupedStudentIds.add(studentId)
          const studentGroup = filteredRecords.value.find(r => r.student_id === studentId)
          if (studentGroup && studentGroup.records) {
            studentGroup.records.forEach(record => {
              const attendance = record.attendance || 'Present'
              if (attendance === 'Absent (Notice Given)') return

              if (record.timer) {
                const sessionKey = `${record.date}-${record.timer}`
                if (!processedSessions.has(sessionKey)) {
                  totalSeconds += parseTimerToSeconds(record.timer)
                  processedSessions.add(sessionKey)
                }
              }
            })
          }
        })
      }

      if (totalSeconds > 0) {
        results.push({
          name: group.name,
          student_count: group.student_count,
          total_duration: formatDurationShort(totalSeconds)
        })
      }
    })
  }

  // 2. Process students NOT in any group (1 Student)
  filteredRecords.value.forEach(studentGroup => {
    if (!groupedStudentIds.has(studentGroup.student_id)) {
      let totalSeconds = 0
      if (studentGroup.records) {
        studentGroup.records.forEach(record => {
          const attendance = record.attendance || 'Present'
          if (attendance === 'Absent (Notice Given)') return
          if (attendance === 'Missed (No Notice)') {
            totalSeconds += 3600
            return
          }
          if (record.timer) {
            totalSeconds += parseTimerToSeconds(record.timer)
          }
        })
      }

      if (totalSeconds > 0) {
        results.push({
          name: studentGroup.student_name,
          student_count: 1,
          total_duration: formatDurationShort(totalSeconds)
        })
      }
    }
  })

  return results
})

const formatDurationShort = (totalSeconds) => {
  const h = Math.floor(totalSeconds / 3600)
  const m = Math.floor((totalSeconds % 3600) / 60)
  const s = totalSeconds % 60
  
  const parts = []
  if (h > 0) parts.push(`${h}hrs`)
  if (m > 0) parts.push(`${m}min`)
  if (s > 0 || (parts.length === 0 && s > 0)) parts.push(`${s}s`)
  if (parts.length === 0) return '0s'
  
  return parts.join(' ')
}

watch(() => props.show, (newVal) => {
  if (newVal && props.tutorId) {
    loadStats()
    loadTimerEditRequests() // Load pending count for badge
  }
})

watch(() => activeTab.value, (newTab) => {
  if (newTab === 'tracker' && stats.value.profile?.email) {
    loadInstructorHours()
  }
  if (newTab === 'notes') {
    loadNotes()
  }
  if (newTab === 'approvals') {
    loadTimerEditRequests()
  }
  if (newTab === 'vacation') {
    loadVacationData()
  }
})

watch(() => leftDateRange.value, () => {
  if (activeTab.value === 'tracker') {
    loadInstructorHours()
  }
})

// Vacation methods
const loadVacationData = async () => {
  if (!props.tutorId) return
  try {
    const response = await axios.get(`/api/admin/tutors/${props.tutorId}/vacations`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success) {
      vacationData.value = response.data.data
      vacationSettings.value.maxDays = response.data.data.max_days || 0
    }
  } catch (error) {
    console.error('Error loading vacation data:', error)
  }
}

const debounceVacationSettings = () => {
  savingVacationSettings.value = true
  if (vacationSettingsTimeout) clearTimeout(vacationSettingsTimeout)
  vacationSettingsTimeout = setTimeout(() => {
    saveVacationSettings()
  }, 1000)
}

const saveVacationSettings = async () => {
  try {
    await axios.post(`/api/admin/tutors/${props.tutorId}/vacation-settings`, {
      max_days: vacationSettings.value.maxDays
    }, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    savingVacationSettings.value = false
    await loadVacationData()
    toast.success('Vacation settings updated')
  } catch (error) {
    console.error('Error saving vacation settings:', error)
    toast.error('Failed to save vacation settings')
    savingVacationSettings.value = false
  }
}

const deleteAdminVacation = async (vacationId) => {
  if (!confirm('Are you sure you want to remove this vacation date?')) return
  try {
    const response = await axios.delete(`/api/admin/tutors/${props.tutorId}/vacations/${vacationId}`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success) {
      toast.success('Vacation date removed')
      await loadVacationData()
    }
  } catch (error) {
    console.error('Error deleting vacation:', error)
    toast.error('Failed to remove vacation date')
  }
}

const approveVacation = async (vacation) => {
  try {
    const response = await axios.post(`/api/admin/tutors/${props.tutorId}/vacations/${vacation.id}/approve`, {
      start_date: vacation.start_date,
      end_date: vacation.end_date
    }, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success) {
      toast.success('Vacation approved')
      await loadVacationData()
    }
  } catch (error) {
    console.error('Error approving vacation:', error)
    toast.error('Failed to approve vacation')
  }
}

const rejectVacation = async (vacationId) => {
  try {
    const response = await axios.post(`/api/admin/tutors/${props.tutorId}/vacations/${vacationId}/reject`, {}, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success) {
      toast.success('Vacation rejected')
      await loadVacationData()
    }
  } catch (error) {
    console.error('Error rejecting vacation:', error)
    toast.error('Failed to reject vacation')
  }
}

// Timer edit request methods

// Group requests by student → then by date within each student
const groupedTimerEdits = computed(() => {
  if (!timerEditRequests.value || timerEditRequests.value.length === 0) return []

  const studentMap = {}
  timerEditRequests.value.forEach(req => {
    const key = req.student_name || 'Unknown'
    if (!studentMap[key]) {
      studentMap[key] = { student_name: key, dateMap: {}, pendingIds: [] }
    }
    const dateKey = req.record_date
    if (!studentMap[key].dateMap[dateKey]) {
      studentMap[key].dateMap[dateKey] = []
    }
    studentMap[key].dateMap[dateKey].push(req)
    if (req.status === 'pending') {
      studentMap[key].pendingIds.push(req.id)
    }
  })

  return Object.values(studentMap).map(sg => ({
    student_name: sg.student_name,
    totalPending: sg.pendingIds.length,
    pendingIds: sg.pendingIds,
    dates: Object.entries(sg.dateMap)
      .sort(([a], [b]) => b.localeCompare(a)) // newest date first
      .map(([date, requests]) => ({ date, requests }))
  })).sort((a, b) => b.totalPending - a.totalPending) // students with more pending first
})

const loadTimerEditRequests = async () => {
  if (!props.tutorId) return
  try {
    const response = await axios.get(`/api/admin/tutors/${props.tutorId}/timer-edit-requests`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success) {
      timerEditRequests.value = response.data.data.requests
      timerEditPendingCount.value = response.data.data.pending_count
    }
  } catch (error) {
    console.error('Error loading timer edit requests:', error)
  }
}

const approveTimerEdit = async (requestId, silent = false) => {
  try {
    const response = await axios.post(`/api/admin/tutors/${props.tutorId}/timer-edit-requests/${requestId}/approve`, {}, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success && !silent) {
      toast.success('Timer edit approved')
      await loadTimerEditRequests()
    }
  } catch (error) {
    console.error('Error approving timer edit:', error)
    if (!silent) toast.error('Failed to approve timer edit')
  }
}

const rejectTimerEdit = async (requestId, silent = false) => {
  try {
    const response = await axios.post(`/api/admin/tutors/${props.tutorId}/timer-edit-requests/${requestId}/reject`, {}, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success && !silent) {
      toast.success('Timer edit rejected')
      await loadTimerEditRequests()
    }
  } catch (error) {
    console.error('Error rejecting timer edit:', error)
    if (!silent) toast.error('Failed to reject timer edit')
  }
}

const approveAllTimerEdits = async () => {
  const pendingIds = timerEditRequests.value.filter(r => r.status === 'pending').map(r => r.id)
  if (!pendingIds.length) return
  if (!confirm(`Approve all ${pendingIds.length} pending timer edit requests?`)) return
  for (const id of pendingIds) {
    await approveTimerEdit(id, true)
  }
  await loadTimerEditRequests()
  toast.success(`All ${pendingIds.length} timer edits approved`)
}

const approveStudentTimerEdits = async (ids) => {
  if (!ids.length) return
  for (const id of ids) {
    await approveTimerEdit(id, true)
  }
  await loadTimerEditRequests()
  toast.success(`${ids.length} timer edits approved`)
}

const rejectStudentTimerEdits = async (ids) => {
  if (!ids.length) return
  if (!confirm(`Reject ${ids.length} timer edit requests?`)) return
  for (const id of ids) {
    await rejectTimerEdit(id, true)
  }
  await loadTimerEditRequests()
  toast.success(`${ids.length} timer edits rejected`)
}

const formatTimerEditDate = (dateStr) => {
  if (!dateStr) return ''
  try {
    const date = new Date(dateStr)
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
  } catch {
    return ''
  }
}

const formatTimerEditDateShort = (dateStr) => {
  if (!dateStr) return ''
  try {
    const date = new Date(dateStr + 'T00:00:00')
    return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })
  } catch {
    return dateStr
  }
}

const formatVacationDate = (dateStr) => {
  const date = new Date(dateStr + 'T00:00:00')
  return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })
}

const calendarMonthLabel = computed(() => {
  const date = new Date(calendarYear.value, calendarMonth.value, 1)
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
})

const prevMonth = () => {
  if (calendarMonth.value === 0) {
    calendarMonth.value = 11
    calendarYear.value--
  } else {
    calendarMonth.value--
  }
}

const nextMonth = () => {
  if (calendarMonth.value === 11) {
    calendarMonth.value = 0
    calendarYear.value++
  } else {
    calendarMonth.value++
  }
}

const calendarDays = computed(() => {
  const firstDay = new Date(calendarYear.value, calendarMonth.value, 1)
  const lastDay = new Date(calendarYear.value, calendarMonth.value + 1, 0)
  const startPadding = firstDay.getDay()
  const days = []

  // Empty cells for padding
  for (let i = 0; i < startPadding; i++) {
    days.push({ date: null })
  }

  // Actual days from ranges
  const vacationDates = (vacationData.value.vacations || []).reduce((acc, v) => {
    let start = new Date(v.start_date + 'T00:00:00')
    let end = new Date(v.end_date + 'T00:00:00')
    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
      acc.add(`${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`)
    }
    return acc
  }, new Set())

  const today = new Date()
  const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`

  for (let d = 1; d <= lastDay.getDate(); d++) {
    const dateStr = `${calendarYear.value}-${String(calendarMonth.value + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    days.push({
      date: dateStr,
      dayNum: d,
      isVacation: vacationDates.has(dateStr),
      isToday: dateStr === todayStr,
    })
  }

  return days
})

const getDayClass = (day) => {
  if (!day.date) return 'text-transparent'
  const classes = ['cursor-default']
  if (day.isVacation) {
    classes.push('bg-emerald-100 text-emerald-800 font-bold border border-emerald-300')
  } else if (day.isToday) {
    classes.push('bg-blue-100 text-blue-800 font-bold border border-blue-300')
  } else {
    classes.push('text-gray-600 hover:bg-gray-50')
  }
  return classes.join(' ')
}
</script>

<style scoped>
/* Additional styling if needed */
</style>

