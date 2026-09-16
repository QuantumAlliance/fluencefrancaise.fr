<template>
  <!-- Outer wrapper: always in DOM when show=true OR when sticky (so timer state persists) -->
  <div>
    <!-- Main modal backdrop: hidden when minimized to sticky -->
    <div
      v-if="show"
      v-show="!isMinimized"
      class="fixed inset-0 bg-black bg-opacity-50 z-50"
    >
    <div class="bg-white w-full h-full overflow-hidden flex flex-col">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center relative">
        <h2 class="text-2xl font-bold text-gray-800">Class Records - {{ studentName }}</h2>
        <div class="flex items-center gap-2">
          <!-- Minimize to sticky while timer is still running -->
          <button
            v-if="anyTimerRunning || (timerStore.isTimerRunning && timerStore.timerOwner === 'student-record')"
            @click="minimizeToSticky"
            class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-gray-800 text-white hover:bg-gray-700 rounded-lg transition-colors"
            title="Minimize — timer keeps running"
          >
            <i class="fas fa-thumbtack text-[10px]"></i>
            Minimize &amp; Keep Timer
          </button>
          <button
            @click="handleCloseAttempt"
            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors"
            title="Close"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="border-b border-gray-200 px-6">
        <div class="flex gap-2 pt-3">
          <button
            @click="activeTab = 'records'"
            :class="activeTab === 'records'
              ? 'bg-[#0055A4] text-white border-[#0055A4] shadow-sm'
              : 'bg-white text-gray-600 border-gray-300 hover:border-[#0055A4] hover:text-[#0055A4]'"
            class="px-5 py-2 text-sm font-semibold border rounded-t-lg transition-colors"
          >
            Records
          </button>
          <button
            @click="activeTab = 'syllabus'"
            :class="activeTab === 'syllabus'
              ? 'bg-[#0055A4] text-white border-[#0055A4] shadow-sm'
              : 'bg-white text-gray-600 border-gray-300 hover:border-[#0055A4] hover:text-[#0055A4]'"
            class="px-5 py-2 text-sm font-semibold border rounded-t-lg transition-colors"
          >
            Syllabus
          </button>
        </div>
      </div>

      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-6">
        <!-- Records Tab -->
        <div v-if="activeTab === 'records'" class="space-y-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Class Records</h3>
            <button
              @click="addRecord"
              class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i class="fas fa-plus"></i>
              Add New Record
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Attendance</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Reason</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Reschedule</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Homework</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Timer</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Notes</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="(record, index) in sortedRecords" 
                  :key="record.id || index" 
                  :class="[
                    'border-b border-gray-200 transition-colors',
                    record.isNew ? 'bg-amber-50 hover:bg-amber-100' : 'hover:bg-gray-50'
                  ]"
                >
                  <td class="px-4 py-3">
                    <flat-pickr
                      v-model="record.date"
                      :config="flatpickrConfig"
                      @input="isDirty = true"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                      placeholder="Select date"
                    ></flat-pickr>
                    <!-- Entry N of M label — only shown when the same date appears more than once -->
                    <div v-if="dateEntryLabel.get(record)" class="mt-1 flex items-center gap-1 flex-wrap">
                      <span
                        :class="record.isNew
                          ? 'bg-amber-100 text-amber-700 border-amber-300'
                          : 'bg-gray-100 text-gray-500 border-gray-300'"
                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold border leading-none"
                      >
                        <span v-if="record.isNew">✦ Unsaved</span>
                        <span v-else>Entry {{ dateEntryLabel.get(record).entry }} of {{ dateEntryLabel.get(record).total }}</span>
                      </span>
                      <span v-if="!record.isNew && record.savedAt"
                        class="text-[10px] text-gray-400 leading-none">
                        · {{ formatSavedTime(record.savedAt) }}
                      </span>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <select
                      v-model="record.attendance"
                      @change="isDirty = true"
                      :class="[
                        'w-full px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4] font-medium transition-colors',
                        record.attendance === 'Present' ? 'bg-green-100 border-green-300 text-green-800' :
                        (record.attendance === 'Absent (Notice Given)' || record.attendance === 'Missed (No Notice)') ? 'bg-red-50 border-red-300 text-red-800' :
                        'bg-white border-gray-300 text-gray-700'
                      ]"
                    >
                      <option value="--">--</option>
                      <option value="Present">Present</option>
                      <option value="Absent (Notice Given)">Absent (Notice Given)</option>
                      <option value="Missed (No Notice)">Missed (No Notice)</option>
                    </select>
                  </td>
                  <td class="px-4 py-3">
                    <input
                      v-model="record.reason"
                      @input="isDirty = true"
                      type="text"
                      placeholder="..."
                      :class="[
                        'w-full px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]',
                        ((record.attendance === 'Absent (Notice Given)' || record.attendance === 'Missed (No Notice)') && (!record.reason || record.reason.trim() === '')) ? 'border-red-500 bg-red-50' : 'border-gray-300'
                      ]"
                    />
                  </td>
                  <td class="px-4 py-3">
                    <flat-pickr
                      v-model="record.reschedule"
                      :config="flatpickrConfig"
                      @input="isDirty = true"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                      placeholder="Select reschedule date"
                    ></flat-pickr>
                  </td>
                  <td class="px-4 py-3">
                    <select
                      v-model="record.homework"
                      @change="isDirty = true"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                    >
                      <option value="Not Given">Not Given</option>
                      <option value="Done">Done</option>
                      <option value="Not Done">Not Done</option>
                      <option value="Partial">Partial</option>
                    </select>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                      <!-- Timer Display or Start Button -->
                      <div v-if="!record.timerRunning && (!record.timer || record.timer.trim() === '') && record.isNew" class="flex-1">
                        <button
                          @click="startTimer(record)"
                          type="button"
                          class="w-full px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded text-sm font-medium transition-colors flex items-center justify-center gap-1.5"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          Start Timer
                        </button>
                      </div>
                      
                      <!-- Running Timer -->
                      <div v-else-if="record.timerRunning" class="flex-1 flex items-center gap-2">
                        <div class="flex-1 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded text-sm font-mono font-bold text-blue-700">
                          {{ formatTimerDisplay(record.timerElapsed) }}
                        </div>
                        <button
                          @click="stopTimer(record)"
                          type="button"
                          class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded text-sm font-medium transition-colors flex items-center gap-1"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                          </svg>
                          Stop
                        </button>
                      </div>
                      
                      <!-- Saved Timer (editable for tutor, readonly for admin) -->
                      <div v-else-if="!record.isNew" class="flex-1">
                        <div class="relative">
                          <!-- Tutor: editable time picker (only for records saved to server with an id) -->
                          <div
                            v-if="record.id && props.apiBase === '/api/tutor' && record.attendance !== 'Absent (Notice Given)' && record.attendance !== 'Missed (No Notice)'"
                          >
                            <!-- Display mode: shows current timer, click to edit -->
                            <div
                              v-if="editingTimerRecordId !== record.id"
                              class="space-y-1"
                            >
                              <div
                                @click="canEditTimer(record) ? openTimerEditor(record) : null"
                                :class="[
                                  'flex items-center gap-1 px-2 py-1 border rounded text-sm font-medium transition-colors',
                                  !canEditTimer(record)
                                    ? 'border-gray-200 bg-gray-50 text-gray-400 cursor-not-allowed'
                                    : getTimerPendingRequest(record)
                                      ? 'border-amber-300 bg-amber-50 text-amber-700 cursor-pointer'
                                      : 'border-gray-300 bg-white text-gray-700 hover:border-[#0055A4] hover:bg-[#0055A4]/5 cursor-pointer'
                                ]"
                                :title="!canEditTimer(record) ? 'Timer edit locked' : 'Click to edit timer'"
                              >
                                <span>{{ record.timer || '—' }}</span>
                                <i v-if="canEditTimer(record)" class="fas fa-pencil-alt text-[10px] text-gray-300 ml-auto"></i>
                                <i v-else class="fas fa-lock text-[10px] text-gray-300 ml-auto" title="Max 2 edits reached"></i>
                              </div>
                              <!-- Status badge for latest edit request -->
                              <div v-if="getTimerLatestRequest(record)" class="flex items-center gap-1">
                                <span
                                  v-if="getTimerLatestRequest(record).status === 'pending'"
                                  class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-200 rounded"
                                  :title="'Requested: ' + getTimerLatestRequest(record).new_timer"
                                >
                                  <i class="fas fa-clock text-[8px]"></i>
                                  Pending: {{ getTimerLatestRequest(record).new_timer }}
                                </span>
                                <span
                                  v-else-if="getTimerLatestRequest(record).status === 'approved'"
                                  class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 rounded"
                                >
                                  <i class="fas fa-check-circle text-[8px]"></i>
                                  Approved
                                </span>
                                <span
                                  v-else-if="getTimerLatestRequest(record).status === 'rejected'"
                                  class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold text-red-500 bg-red-50 border border-red-200 rounded"
                                  :title="'Rejected: ' + getTimerLatestRequest(record).new_timer"
                                >
                                  <i class="fas fa-times-circle text-[8px]"></i>
                                  Rejected: {{ getTimerLatestRequest(record).new_timer }}
                                </span>
                              </div>
                            </div>

                            <!-- Edit mode: h / m / s pickers -->
                            <div
                              v-else
                              class="flex items-center gap-0.5 p-1 border-2 border-[#0055A4] rounded-lg bg-white shadow-sm"
                            >
                              <div class="flex items-center">
                                <input
                                  type="number"
                                  min="0"
                                  max="23"
                                  v-model.number="timerEditHours"
                                  @keydown.enter="submitTimerEdit(record)"
                                  class="w-9 text-center text-sm font-bold border border-gray-200 rounded py-0.5 focus:outline-none focus:border-[#0055A4] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                                <span class="text-[10px] font-bold text-gray-400 mx-0.5">h</span>
                              </div>
                              <div class="flex items-center">
                                <input
                                  type="number"
                                  min="0"
                                  max="59"
                                  v-model.number="timerEditMinutes"
                                  @keydown.enter="submitTimerEdit(record)"
                                  class="w-9 text-center text-sm font-bold border border-gray-200 rounded py-0.5 focus:outline-none focus:border-[#0055A4] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                                <span class="text-[10px] font-bold text-gray-400 mx-0.5">m</span>
                              </div>
                              <div class="flex items-center">
                                <input
                                  type="number"
                                  min="0"
                                  max="59"
                                  v-model.number="timerEditSeconds"
                                  @keydown.enter="submitTimerEdit(record)"
                                  class="w-9 text-center text-sm font-bold border border-gray-200 rounded py-0.5 focus:outline-none focus:border-[#0055A4] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                                <span class="text-[10px] font-bold text-gray-400 mx-0.5">s</span>
                              </div>
                              <button
                                @click="submitTimerEdit(record)"
                                class="ml-1 px-1.5 py-0.5 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded text-[10px] font-bold transition-colors"
                                title="Submit for approval"
                              >
                                <i class="fas fa-check"></i>
                              </button>
                              <button
                                @click="cancelTimerEditor()"
                                class="px-1.5 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded text-[10px] font-bold transition-colors"
                                title="Cancel"
                              >
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>

                          <!-- Admin or absent: readonly display -->
                          <input
                            v-else
                            :value="(record.attendance === 'Absent (Notice Given)' || record.attendance === 'Missed (No Notice)') ? '—' : record.timer"
                            readonly
                            type="text"
                            :class="[
                              'w-full px-2 py-1 border rounded text-sm font-medium',
                              (record.attendance === 'Absent (Notice Given)' || record.attendance === 'Missed (No Notice)')
                                ? 'border-gray-200 bg-gray-100 text-gray-400'
                                : 'border-gray-300 bg-gray-50 text-gray-700'
                            ]"
                          />
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <input
                      v-model="record.notes"
                      @input="isDirty = true"
                      type="text"
                      placeholder="..."
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                    />
                  </td>
                  <td class="px-4 py-3">
                    <button
                      @click="deleteRecord(record)"
                      class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="records.length === 0">
                  <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                    No records found. Click "Add New Record" to create one.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Syllabus Tab -->
        <div v-if="activeTab === 'syllabus'" class="space-y-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Syllabus Progress</h3>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">French Levels</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Topics</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Completed</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(item, index) in syllabus" :key="index">
                  <tr v-if="isFirstInLevel(item.level, index)" class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-800 font-medium align-top" :rowspan="getLevelRowspan(item.level, index)">
                      {{ item.level }}
                    </td>
                    <td class="px-4 py-3 text-gray-800">{{ item.topic }}</td>
                    <td class="px-4 py-3">
                      <select
                        v-model="item.status"
                        :class="[
                          'w-full px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4] font-medium',
                          item.status === 'Completed' ? 'bg-green-100 border-green-300 text-green-800' :
                          item.status === 'In Progress' ? 'bg-yellow-100 border-yellow-300 text-yellow-800' :
                          'bg-gray-100 border-gray-300 text-gray-600'
                        ]"
                      >
                        <option value="--">--</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                      </select>
                    </td>
                    <td class="px-4 py-3">
                      <input
                        v-model="item.date"
                        type="date"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                      />
                    </td>
                  </tr>
                  <tr v-else class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-800">{{ item.topic }}</td>
                    <td class="px-4 py-3">
                      <select
                        v-model="item.status"
                        :class="[
                          'w-full px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4] font-medium',
                          item.status === 'Completed' ? 'bg-green-100 border-green-300 text-green-800' :
                          item.status === 'In Progress' ? 'bg-yellow-100 border-yellow-300 text-yellow-800' :
                          'bg-gray-100 border-gray-300 text-gray-600'
                        ]"
                      >
                        <option value="--">--</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                      </select>
                    </td>
                    <td class="px-4 py-3">
                      <input
                        v-model="item.date"
                        type="date"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-[#0055A4]"
                      />
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center gap-4">
        <!-- Cancel (left) -->
        <button
          @click="handleCloseAttempt"
          class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors"
        >
          Cancel
        </button>
        <!-- Save (right) -->
        <div class="flex gap-3">
        <button
          @click="saveRecords"
          v-if="activeTab === 'records'"
          :disabled="saving || loading"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="saving" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ saving ? 'Saving...' : 'Save Changes' }}</span>
        </button>
        <button
          @click="saveSyllabus"
          v-if="activeTab === 'syllabus'"
          :disabled="saving || loading"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="saving" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ saving ? 'Saving...' : 'Save Changes' }}</span>
        </button>
        </div><!-- end flex gap-3 -->
      </div><!-- end footer -->
    </div>
  </div>

  <!-- Unsaved Changes Confirmation Modal -->
  <div
    v-if="showUnsavedConfirm"
    class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center"
    @click.self="showUnsavedConfirm = false"
  >
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-900">Discard unsaved changes?</h3>
            <p class="text-sm text-gray-600 mt-1">You have unsaved changes. Do you want to save them before closing?</p>
          </div>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
        <button
          @click="showUnsavedConfirm = false"
          class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-sm"
        >
          Keep Editing
        </button>
        <div class="flex items-center gap-2">
          <button
            @click="confirmClose"
            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors flex items-center gap-1.5 text-sm"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Discard
          </button>
          <button
            @click="saveAndClose"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center gap-1.5 text-sm"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save & Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Stop Timer Confirmation Dialog (Individual) -->
  <div
    v-if="showStopTimerConfirm"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10010]"
  >
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 max-w-md w-full mx-4 overflow-hidden">
      <!-- Header -->
      <div class="px-6 py-4 bg-red-600">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
            <i class="fas fa-stop-circle text-white text-xl"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-white">Stop Session Timer?</h3>
            <p class="text-white/80 text-sm">Current session: {{ formatTimerDisplay(recordToStop?.timerElapsed || 0) }}</p>
          </div>
        </div>
      </div>
      
      <!-- Content -->
      <div class="p-6">
        <p class="text-gray-600 mb-4">
          Are you sure you want to stop the session timer? The recorded time will be saved with the attendance.
        </p>
        <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
          <span class="text-3xl font-bold text-gray-800 font-mono">{{ formatTimerDisplay(recordToStop?.timerElapsed || 0) }}</span>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-3">
        <button
          @click="cancelStopTimer"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
        >
          Continue Timer
        </button>
        <button
          @click="confirmStopTimer"
          class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
        >
          <i class="fas fa-stop"></i>
          Stop Timer
        </button>
      </div>
    </div>
  </div>

  <!-- ─── Timer Conflict Dialog ─── -->
  <div
    v-if="showTimerConflictDialog"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10020]"
  >
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 max-w-md w-full mx-4 overflow-hidden">
      <div class="px-6 py-4 bg-amber-500">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
          </div>
          <h3 class="text-lg font-bold text-white">Timer Already Running</h3>
        </div>
      </div>
      <div class="p-6">
        <p class="text-gray-600">{{ timerConflictMessage }}</p>
      </div>
      <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
        <button
          @click="showTimerConflictDialog = false"
          class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-colors"
        >
          OK, Got It
        </button>
      </div>
    </div>
  </div>

  <!-- ─── Sticky Floating Timer Widget (teleported to body) ─── -->
  <!-- Shown when modal is minimized while a per-record timer is running -->
  <Teleport to="body">
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-4 scale-95"
    >
      <div
        v-if="isMinimized && (anyTimerRunning || timerStore.isTimerRunning) && timerStore.timerOwner === 'student-record'"
        class="fixed right-6 z-[9999] select-none transition-all duration-300"
        :class="isGroupTimerSticky ? 'bottom-56' : 'bottom-6'"
      >
        <div class="bg-gray-900 text-white rounded-2xl shadow-2xl border border-white/10 overflow-hidden min-w-[260px]">
          <!-- Live pulse indicator + label -->
          <div class="flex items-center gap-2 px-4 pt-3 pb-1">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
            </span>
            <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest">Timer Running</span>
          </div>

          <!-- Student name -->
          <div class="px-4 pb-1">
            <p class="text-xs text-gray-400 truncate max-w-[200px]">
              <i class="fas fa-user mr-1"></i>{{ studentName }}
            </p>
          </div>

          <!-- Timer display -->
          <div class="px-4 py-2 flex items-center justify-center">
            <span class="text-4xl font-mono font-bold tracking-tight text-white">
              {{ formatTimerDisplay(runningTimerRecord?.timerElapsed ?? timerStore.sessionTimerSeconds) }}
            </span>
          </div>

          <!-- Wake lock hint -->
          <div class="px-4 pb-2 text-center">
            <p class="text-[10px] text-gray-500">
              <i class="fas fa-lock-open mr-1"></i>Screen will stay awake
            </p>
          </div>

          <!-- Actions -->
          <div class="flex border-t border-white/10">
            <button
              @click="restoreFromSticky"
              class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-colors"
            >
              <i class="fas fa-expand-alt text-xs"></i>
              Reopen
            </button>
            <div class="w-px bg-white/10"></div>
            <button
              @click="stopTimerFromSticky"
              class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors"
            >
              <i class="fas fa-stop-circle text-xs"></i>
              Stop
            </button>
          </div>
        </div>
      </div>
    </transition>
  </Teleport>

  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import { useToast } from '../composables/useToast'
import FlatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import { useTimerStore } from '../stores/timer'

const props = defineProps({
  show: Boolean,
  studentId: Number,
  studentName: String,
  apiBase: {
    type: String,
    default: '/api/admin' // Can be '/api/admin' or '/api/tutor'
  },
  isGroupTimerSticky: Boolean
})

const emit = defineEmits(['close'])

const toast = useToast()
const activeTab = ref('records')
const records = ref([])
const syllabus = ref([])
const loading = ref(false)
const saving = ref(false)
const isDirty = ref(false)
const showUnsavedConfirm = ref(false)
const isMinimized = ref(false) // true = modal hidden, sticky widget visible
const timerStore = useTimerStore()


// Flatpickr config
const flatpickrConfig = {
  dateFormat: 'Y-m-d',
  allowInput: true
}

// Computed property for reversed records (newest first)
const sortedRecords = computed(() => {
  return [...records.value].reverse()
})

// Whether any per-record timer is currently running
const anyTimerRunning = computed(() => records.value.some(r => r.timerRunning))
// The first running timer record (for the sticky widget display)
const runningTimerRecord = computed(() => records.value.find(r => r.timerRunning))

// For each record, compute its "Entry N of M" label when the same date appears more than once.
// sortedRecords is newest-first, so we number them in that display order.
const dateEntryLabel = computed(() => {
  const labelMap = new Map()
  const countMap = {}
  // Count total occurrences per date
  sortedRecords.value.forEach(r => {
    if (r.date) countMap[r.date] = (countMap[r.date] || 0) + 1
  })
  // Assign sequential numbers in display order
  const seenMap = {}
  sortedRecords.value.forEach(r => {
    if (!r.date || countMap[r.date] < 2) return
    seenMap[r.date] = (seenMap[r.date] || 0) + 1
    labelMap.set(r, { entry: seenMap[r.date], total: countMap[r.date] })
  })
  return labelMap
})

// No manual store sync needed here — timerStore.startTimer() handles isTimerRunning/interval,
// and timerStore.resetTimer() is called explicitly on stop. The store's module-level interval
// survives component unmounts so the sticky widget keeps ticking during navigation.

// Default syllabus structure matching the Excel file
const defaultSyllabus = [
  // A1 Level
  { level: 'A1', topic: 'The French Alphabet', date: '', status: '--' },
  { level: 'A1', topic: 'Accent Marks (é, è, ê, ë, ç, etc.)', date: '', status: '--' },
  { level: 'A1', topic: 'Letter Combinations (Ç, AI, EI, AN, AM, EN, EM, ...)', date: '', status: '--' },
  { level: 'A1', topic: 'Silent Letters (D, E, G, H, S, T, X) and nasal vowels', date: '', status: '--' },
  { level: 'A1', topic: 'Basic Greetings and Farewells', date: '', status: '--' },
  { level: 'A1', topic: 'Introducing Yourself', date: '', status: '--' },
  { level: 'A1', topic: 'Classroom/survival phrases; tu vs vous, pardon, répétez s\'il vous plaît', date: '', status: '--' },
  { level: 'A1', topic: 'Numbers (0-100) & intro to ordinal numbers', date: '', status: '--' },
  { level: 'A1', topic: 'Days of the Week and Months', date: '', status: '--' },
  { level: 'A1', topic: 'Telling the Time', date: '', status: '--' },
  { level: 'A1', topic: 'Telling the Date', date: '', status: '--' },
  { level: 'A1', topic: 'Weather', date: '', status: '--' },
  { level: 'A1', topic: 'Definite and Indefinite Articles (le, la, un, une, etc.)', date: '', status: '--' },
  { level: 'A1', topic: 'Gender & plural of nouns', date: '', status: '--' },
  { level: 'A1', topic: 'Contractions with à/de (au, du, aux, des)', date: '', status: '--' },
  { level: 'A1', topic: 'C\'est / Ce sont', date: '', status: '--' },
  { level: 'A1', topic: 'Il y a', date: '', status: '--' },
  { level: 'A1', topic: 'Subject Pronouns (je, tu, il, elle, etc.)', date: '', status: '--' },
  { level: 'A1', topic: 'Basic Verbs in Present Tense (être, avoir, aller, faire)', date: '', status: '--' },
  { level: 'A1', topic: 'First Group Verbs (-er verbs)', date: '', status: '--' },
  { level: 'A1', topic: 'Spelling-change -er verbs (mangeons, commençons, j\'achète, je préfère, j\'appelle)', date: '', status: '--' },
  { level: 'A1', topic: 'Introduction to Infinitive with 1st group verbs', date: '', status: '--' },
  { level: 'A1', topic: 'Basic Negation (ne...pas)', date: '', status: '--' },
  { level: 'A1', topic: 'Asking Questions (est-ce que, intonation)', date: '', status: '--' },
  { level: 'A1', topic: 'Basic connectors (et, mais, ou, parce que, donc)', date: '', status: '--' },
  { level: 'A1', topic: 'Common Adjectives', date: '', status: '--' },
  { level: 'A1', topic: 'Descriptive Adjectives', date: '', status: '--' },
  { level: 'A1', topic: 'Adjective agreement & position (basics)', date: '', status: '--' },
  { level: 'A1', topic: 'Basic Physical description', date: '', status: '--' },
  { level: 'A1', topic: 'Colors', date: '', status: '--' },
  { level: 'A1', topic: 'Clothing', date: '', status: '--' },
  { level: 'A1', topic: 'Family Members', date: '', status: '--' },
  { level: 'A1', topic: 'Animals', date: '', status: '--' },
  { level: 'A1', topic: 'Countries, cities, nationalities and prepositions', date: '', status: '--' },
  { level: 'A1', topic: 'Parts of the Body', date: '', status: '--' },
  { level: 'A1', topic: 'Furniture and household items', date: '', status: '--' },
  { level: 'A1', topic: 'Food and Drinks', date: '', status: '--' },
  { level: 'A1', topic: 'Prepositions of Place (sur, sous, devant, etc.)', date: '', status: '--' },
  { level: 'A1', topic: 'Talking About Daily Routines', date: '', status: '--' },
  { level: 'A1', topic: 'Reflexive verbs (present) (se lever, s\'appeler...)', date: '', status: '--' },
  { level: 'A1', topic: 'Imperative (tu / nous / vous)', date: '', status: '--' },
  { level: 'A1', topic: 'Second-group verbs (-ir, -issons) present', date: '', status: '--' },
  { level: 'A1', topic: 'Prepositions of time (à + hour; en + month/year)', date: '', status: '--' },
  { level: 'A1', topic: 'Partitive basics (du, de la, de l\', des → pas de)', date: '', status: '--' },
  { level: 'A1', topic: 'Third-group basics (present) (prendre, venir, partir/sortir/dormir)', date: '', status: '--' },
  { level: 'A1', topic: 'Means of transport (le train, la voiture)', date: '', status: '--' },
  { level: 'A1', topic: 'Jouer + Sports', date: '', status: '--' },
  { level: 'A1', topic: 'Jouer + Musical instruments', date: '', status: '--' },
  { level: 'A1', topic: 'Transport prepositions: à / en', date: '', status: '--' },
  { level: 'A1', topic: 'Possessive Adjectives', date: '', status: '--' },
  { level: 'A1', topic: 'Demonstrative adjectives (ce/cet/cette/ces)', date: '', status: '--' },
  { level: 'A1', topic: 'Possession with de (le livre de Marie)', date: '', status: '--' },
  { level: 'A1', topic: 'Forming Negative Sentences', date: '', status: '--' },
  { level: 'A1', topic: 'Interrogative Words', date: '', status: '--' },
  { level: 'A1', topic: 'Forming Interrogative Sentences', date: '', status: '--' },
  { level: 'A1', topic: 'Basic Connectors', date: '', status: '--' },
  { level: 'A1', topic: 'Prices & shopping (combien, ça coûte...)', date: '', status: '--' },
  { level: 'A1', topic: 'Places in town', date: '', status: '--' },
  { level: 'A1', topic: 'Directions in town (à gauche/droite, tout droit)', date: '', status: '--' },
  { level: 'A1', topic: 'Infinitive with 1st, 2nd, and 3rd group verbs', date: '', status: '--' },
  { level: 'A1', topic: '10 Expressions & Idioms', date: '', status: '--' },
  { level: 'A1', topic: 'Practice Exercises A1', date: '', status: '--' },
  { level: 'A1', topic: 'A1 Test', date: '', status: '--' },
  // A2 Level
  { level: 'A2', topic: 'Numbers 100-1,000,000', date: '', status: '--' },
  { level: 'A2', topic: 'Ordinal Numbers (1er, 2e, 3e...)', date: '', status: '--' },
  { level: 'A2', topic: 'Adverbs of Frequency', date: '', status: '--' },
  { level: 'A2', topic: 'Adverbs of Manner', date: '', status: '--' },
  { level: 'A2', topic: 'Comparatives & superlatives (plus/moins/aussi... que; meilleur/mieux; le plus/le moins)', date: '', status: '--' },
  { level: 'A2', topic: 'Partitive Articles', date: '', status: '--' },
  { level: 'A2', topic: 'Quantifiers (beaucoup de, assez de, trop de, peu de, autant de)', date: '', status: '--' },
  { level: 'A2', topic: 'Partitive in negation (du/de la/des → pas de)', date: '', status: '--' },
  { level: 'A2', topic: 'Introduction to le Passé Récent', date: '', status: '--' },
  { level: 'A2', topic: 'Reflexive Verbs (present - quick review)', date: '', status: '--' },
  { level: 'A2', topic: 'Le Passé Composé (1st, 2nd, 3rd group verbs)', date: '', status: '--' },
  { level: 'A2', topic: 'Le Passé Composé: être or avoir', date: '', status: '--' },
  { level: 'A2', topic: 'Reflexive verbs in passé composé (me/te/se/nous/vous + être)', date: '', status: '--' },
  { level: 'A2', topic: 'L\'Imparfait', date: '', status: '--' },
  { level: 'A2', topic: 'Le Passé Composé vs L\'Imparfait', date: '', status: '--' },
  { level: 'A2', topic: 'Depuis / Pendant / Pour / il y a', date: '', status: '--' },
  { level: 'A2', topic: 'COD & Direct Object Pronouns', date: '', status: '--' },
  { level: 'A2', topic: 'COI & Indirect Object Pronouns', date: '', status: '--' },
  { level: 'A2', topic: 'Pronoun order (infinitive & passé composé)', date: '', status: '--' },
  { level: 'A2', topic: 'Relative Pronouns: qui, que & où', date: '', status: '--' },
  { level: 'A2', topic: 'Le Participe (participe présent / rappel participe passé)', date: '', status: '--' },
  { level: 'A2', topic: 'Le Gérondif', date: '', status: '--' },
  { level: 'A2', topic: 'Futur Proche (aller + infinitif)', date: '', status: '--' },
  { level: 'A2', topic: 'Le Futur Simple', date: '', status: '--' },
  { level: 'A2', topic: 'Futur proche vs futur simple (usage)', date: '', status: '--' },
  { level: 'A2', topic: 'Modal verbs + infinitive (devoir / pouvoir / vouloir)', date: '', status: '--' },
  { level: 'A2', topic: 'Infinitive of purpose & sequence (pour / afin de / sans / avant de + infinitif)', date: '', status: '--' },
  { level: 'A2', topic: 'Talking About Future Plans', date: '', status: '--' },
  { level: 'A2', topic: 'L\'Impératif', date: '', status: '--' },
  { level: 'A2', topic: 'Means of Transport (la voiture, le train)', date: '', status: '--' },
  { level: 'A2', topic: 'Transport prepositions à / en', date: '', status: '--' },
  { level: 'A2', topic: 'Asking for and Giving Directions', date: '', status: '--' },
  { level: 'A2', topic: 'Describing Daily Life', date: '', status: '--' },
  { level: 'A2', topic: 'Health and Body Vocabulary', date: '', status: '--' },
  { level: 'A2', topic: 'Jobs & Professions', date: '', status: '--' },
  { level: 'A2', topic: 'Demonstrative Adjectives and Pronouns', date: '', status: '--' },
  { level: 'A2', topic: 'Possessive Pronouns (le mien, le tien, etc.)', date: '', status: '--' },
  { level: 'A2', topic: '10 Expressions & Idioms', date: '', status: '--' },
  { level: 'A2', topic: 'Practice Exercises A2', date: '', status: '--' },
  { level: 'A2', topic: 'A2 Test', date: '', status: '--' },
  // B1 Level
  { level: 'B1', topic: 'Le Passé Récent', date: '', status: '--' },
  { level: 'B1', topic: 'Plus-que-parfait', date: '', status: '--' },
  { level: 'B1', topic: 'Le Passé Composé VS Le Plus-que-parfait', date: '', status: '--' },
  { level: 'B1', topic: 'Conditionnel Présent', date: '', status: '--' },
  { level: 'B1', topic: 'Futur Simple VS Conditionnel Présent', date: '', status: '--' },
  { level: 'B1', topic: 'Si Clauses (type 1 & 2)', date: '', status: '--' },
  { level: 'B1', topic: 'Si Clauses (type 3 - conditionnel passé)', date: '', status: '--' },
  { level: 'B1', topic: 'Relative Pronouns (qui, que, où)', date: '', status: '--' },
  { level: 'B1', topic: 'Relative Pronoun "dont" (intro)', date: '', status: '--' },
  { level: 'B1', topic: 'Relative Pronoun "lequel"', date: '', status: '--' },
  { level: 'B1', topic: 'Infinitive Constructions', date: '', status: '--' },
  { level: 'B1', topic: 'Causative: faire + infinitif', date: '', status: '--' },
  { level: 'B1', topic: 'Passive Voice (simple tenses)', date: '', status: '--' },
  { level: 'B1', topic: 'Passive Voice (compound tenses)', date: '', status: '--' },
  { level: 'B1', topic: 'Pronoun "Y"', date: '', status: '--' },
  { level: 'B1', topic: 'Pronoun "En"', date: '', status: '--' },
  { level: 'B1', topic: 'Indefinite Pronouns', date: '', status: '--' },
  { level: 'B1', topic: 'Compound Prepositions', date: '', status: '--' },
  { level: 'B1', topic: 'Prepositions with Countries, Regions & Cities', date: '', status: '--' },
  { level: 'B1', topic: 'Indirect Speech', date: '', status: '--' },
  { level: 'B1', topic: 'Reported Questions in Indirect Speech', date: '', status: '--' },
  { level: 'B1', topic: 'Impersonal Verbs', date: '', status: '--' },
  { level: 'B1', topic: 'Le Subjonctif Présent', date: '', status: '--' },
  { level: 'B1', topic: 'L\'Indicatif VS Le Subjonctif', date: '', status: '--' },
  { level: 'B1', topic: 'Expressing Cause and Effect', date: '', status: '--' },
  { level: 'B1', topic: 'Cause/Consequence nuance (à cause de, grâce à, en raison de)', date: '', status: '--' },
  { level: 'B1', topic: 'Connector pack (cependant, pourtant, en revanche, puisque, par conséquent, en outre)', date: '', status: '--' },
  { level: 'B1', topic: 'Nominalization', date: '', status: '--' },
  { level: 'B1', topic: 'Using "Tout"', date: '', status: '--' },
  { level: 'B1', topic: 'Advanced Negatives (ne... jamais, ne... plus, etc.)', date: '', status: '--' },
  { level: 'B1', topic: 'Independent and Subordinate Clauses', date: '', status: '--' },
  { level: 'B1', topic: 'Emphatic Pronouns (moi, toi, etc.)', date: '', status: '--' },
  { level: 'B1', topic: 'Pronunciation: liaison & enchaînement', date: '', status: '--' },
  { level: 'B1', topic: 'Les Faux Amis', date: '', status: '--' },
  { level: 'B1', topic: 'Opinion and Debate Phrases', date: '', status: '--' },
  { level: 'B1', topic: 'Formal and Informal Email Writing', date: '', status: '--' },
  { level: 'B1', topic: 'Structuring Long Arguments', date: '', status: '--' },
  { level: 'B1', topic: '10 Expressions & Idioms', date: '', status: '--' },
  { level: 'B1', topic: 'B1 Test', date: '', status: '--' },
  // B2 Level - Tense & Mood Review
  { level: 'B2', topic: 'Tense & Mood Review', date: '', status: '--' },
  { level: 'B2', topic: 'Révision des temps (j\'ai fini; j\'étais; je partirai)', date: '', status: '--' },
  { level: 'B2', topic: 'Futur antérieur (quand il sera arrivé...)', date: '', status: '--' },
  { level: 'B2', topic: 'Conditionnel passé', date: '', status: '--' },
  { level: 'B2', topic: 'Phrases conditionnelles 2-3, mixtes (si j\'avais su... je serais venu)', date: '', status: '--' },
  { level: 'B2', topic: 'Conditionnel « journalistique » (il aurait démissionné)', date: '', status: '--' },
  { level: 'B2', topic: 'Subjonctif passé', date: '', status: '--' },
  { level: 'B2', topic: 'Concession / but / conséquence au subjonctif (bien que...; pour que...)', date: '', status: '--' },
  { level: 'B2', topic: 'Infinitif passé', date: '', status: '--' },
  // B2 Level - Reported Speech & Time References
  { level: 'B2', topic: 'Reported Speech & Time References', date: '', status: '--' },
  { level: 'B2', topic: 'Discours indirect: concordance (il a dit qu\'il viendrait)', date: '', status: '--' },
  { level: 'B2', topic: 'Repères temporels avancés', date: '', status: '--' },
  { level: 'B2', topic: 'Atténuation / modalisation (il se peut que...; il semblerait que...)', date: '', status: '--' },
  // B2 Level - Pronouns & Complex Constructions
  { level: 'B2', topic: 'Pronouns & Complex Constructions', date: '', status: '--' },
  { level: 'B2', topic: 'Pronoms relatifs : ce qui / ce que / ce dont', date: '', status: '--' },
  { level: 'B2', topic: 'Pronoms relatifs: auquel/duquel + démonstratifs (la ville à laquelle...; celui dont...)', date: '', status: '--' },
  { level: 'B2', topic: 'Ordre des pronoms (le lui; lui en; donne-le-moi)', date: '', status: '--' },
  { level: 'B2', topic: 'Y & EN (avancé) (j\'y tiens; je m\'en souviens)', date: '', status: '--' },
  { level: 'B2', topic: 'Verbes avec « s\'en » (il s\'en va; elle s\'en sort)', date: '', status: '--' },
  { level: 'B2', topic: 'Pronoms indéfinis (quelqu\'un; aucun; plusieurs)', date: '', status: '--' },
  { level: 'B2', topic: 'Pronoms démonstratifs indéfinis (ceci; cela; ça)', date: '', status: '--' },
  { level: 'B2', topic: 'Ne explétif (avant qu\'il ne parte)', date: '', status: '--' },
  { level: 'B2', topic: 'Euphonic "ne" in stylistic/formal writing', date: '', status: '--' },
  { level: 'B2', topic: 'Constructions causatives (faire/se faire/se laisser/se voir + inf.)', date: '', status: '--' },
  { level: 'B2', topic: 'Double causative with reflexives (Elle s\'est fait couper les cheveux)', date: '', status: '--' },
  { level: 'B2', topic: 'Passif pronominal', date: '', status: '--' },
  // B2 Level - Syntax & Sentence Variety
  { level: 'B2', topic: 'Syntax & Sentence Variety', date: '', status: '--' },
  { level: 'B2', topic: 'Inversions complexes', date: '', status: '--' },
  { level: 'B2', topic: 'Non seulement... mais aussi (+ inversion) (non seulement a-t-il accepté...)', date: '', status: '--' },
  { level: 'B2', topic: 'Clivage pour insistance (c\'est... qui/que) (c\'est moi qui décide)', date: '', status: '--' },
  { level: 'B2', topic: 'On vs L\'on (on dit...; l\'on constate...)', date: '', status: '--' },
  // B2 Level - Vocabulary & Grammar Refinement
  { level: 'B2', topic: 'Vocabulary & Grammar Refinement', date: '', status: '--' },
  { level: 'B2', topic: 'Prépositions avancées (dès lundi; à partir de...)', date: '', status: '--' },
  { level: 'B2', topic: 'Rection verbale avancée (verbe + à/de + inf.) (se mettre à...; permettre de...)', date: '', status: '--' },
  { level: 'B2', topic: 'Articles : révision (du café; sans sucre)', date: '', status: '--' },
  { level: 'B2', topic: 'C\'est vs Il est (c\'est un problème; il est important de...)', date: '', status: '--' },
  { level: 'B2', topic: 'Adjectifs avancés (une ancienne collègue; bleu clair)', date: '', status: '--' },
  { level: 'B2', topic: 'Pluriels irréguliers (œil/yeux; travail/travaux)', date: '', status: '--' },
  { level: 'B2', topic: 'Préfixes & suffixes (imprévisible; refaire)', date: '', status: '--' },
  { level: 'B2', topic: 'Quantificateurs avancés (la plupart; un grand nombre de; maints)', date: '', status: '--' },
  // B2 Level - Discourse & Style
  { level: 'B2', topic: 'Discourse & Style', date: '', status: '--' },
  { level: 'B2', topic: 'Organisateurs & connecteurs avancés (d\'abord... ensuite... toutefois...)', date: '', status: '--' },
  { level: 'B2', topic: 'Connectors of hypothesis (au cas où, à condition que, pourvu que)', date: '', status: '--' },
  { level: 'B2', topic: 'Paraphrase & reformulation (autrement dit...; en d\'autres termes...)', date: '', status: '--' },
  { level: 'B2', topic: 'Résumé & synthèse (idée principale; en résumé...)', date: '', status: '--' },
  { level: 'B2', topic: 'Ponctuation formelle (« Bonjour ! »; espace avant : ; ? !)', date: '', status: '--' },
  { level: 'B2', topic: 'Rédaction structurée (thèse → arguments → conclusion)', date: '', status: '--' },
  { level: 'B2', topic: 'Ironie & métaphore (« C\'est du propre ! »; marathon)', date: '', status: '--' },
  { level: 'B2', topic: 'Lecture authentique : reconnaissance (il arriva; quoiqu\'il fût...)', date: '', status: '--' },
  { level: 'B2', topic: 'Expressions & idiomes (tenir au courant; faire le point; tomber à pic...)', date: '', status: '--' },
  // Final Prep
  { level: 'Final Prep', topic: 'Final Prep', date: '', status: '--' },
  { level: 'Final Prep', topic: 'Révisions finales (quiz + remédiation)', date: '', status: '--' },
  { level: 'Final Prep', topic: 'Practice Exam', date: '', status: '--' },
  { level: 'Final Prep', topic: 'PrepMyFuture', date: '', status: 'In Progress' }
]

const addRecord = () => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  const formattedDate = `${year}-${month}-${day}`
  records.value.push({
    id: null,          // backend assigns a stable ID on first save
    date: formattedDate,
    attendance: '--',
    reason: '',
    reschedule: '',
    homework: 'Not Given',
    timer: '',
    timerRunning: false,
    timerStartTime: null,
    timerElapsed: 0,
    timerInterval: null,
    notes: '',
    savedAt: null,
    isNew: true
  })
}

const deleteRecord = (record) => {
  if (confirm('Are you sure you want to delete this record?')) {
    if (record.timerInterval) {
      clearInterval(record.timerInterval)
    }
    const idx = records.value.indexOf(record)
    if (idx !== -1) {
      records.value.splice(idx, 1)
    }
  }
}


// ─── Wake Lock helpers ──────────────────────────────────────────────────────
let wakeLock = null

const acquireWakeLock = async () => {
  if ('wakeLock' in navigator) {
    try {
      wakeLock = await navigator.wakeLock.request('screen')
      document.addEventListener('visibilitychange', reacquireWakeLock)
    } catch (err) {
      console.warn('Wake Lock could not be acquired:', err)
    }
  }
}

const reacquireWakeLock = async () => {
  const anyRunning = records.value.some(r => r.timerRunning)
  if (!document.hidden && anyRunning && !wakeLock) {
    try { wakeLock = await navigator.wakeLock.request('screen') } catch (_) {}
  }
}

const releaseWakeLock = () => {
  document.removeEventListener('visibilitychange', reacquireWakeLock)
  if (wakeLock) { wakeLock.release().catch(() => {}); wakeLock = null }
}

// ─── Timer helpers ───────────────────────────────────────────────────────────
// Auto-stop any running timer and return the formatted string for that record.
// Called before save/close so nothing is ever lost.
const autoStopRunningTimers = () => {
  let stoppedAny = false
  records.value.forEach(record => {
    if (record.timerRunning) {
      if (record.timerInterval) {
        clearInterval(record.timerInterval)
        record.timerInterval = null
      }
      record.timerRunning = false
      record.timer = formatTimerDisplay(record.timerElapsed)
      record.isNew = false
      isDirty.value = true
      stoppedAny = true
    }
  })
  releaseWakeLock()
  // Only reset the store if a record timer was actually running here.
  // If records were reloaded from the API (no timerRunning flags), the store
  // timer is still live (minimized session) and must NOT be touched.
  if (stoppedAny && timerStore.timerOwner === 'student-record') {
    timerStore.resetTimer()
  }
}

// Timer conflict dialog state
const showTimerConflictDialog = ref(false)
const timerConflictMessage = ref('')

// Timer Functions
const startTimer = (record) => {
  // Conflict: a group session timer is already running
  if (timerStore.isTimerRunning && timerStore.timerOwner === 'group') {
    timerConflictMessage.value = 'A group session timer is currently running. Please stop it before starting a new session timer.'
    showTimerConflictDialog.value = true
    return
  }
  // Conflict: another student record timer is already running in this modal
  if (anyTimerRunning.value) {
    timerConflictMessage.value = 'A session timer is already running for another record. Please stop it first.'
    showTimerConflictDialog.value = true
    return
  }

  record.timerStartTime = Date.now()
  record.timerElapsed = 0
  record.timerRunning = true
  isDirty.value = true

  // Start the store's module-level interval (survives component unmounts / page navigation).
  // Store student identity so "Go to Session" can re-open the correct modal on return.
  timerStore.activeGroupName = props.studentName
  timerStore.timerStudentId = props.studentId
  timerStore.timerStudentName = props.studentName
  timerStore.startTimer(0, 'student-record')

  // Prevent screen sleep while timer is running
  acquireWakeLock()

  // Component-level interval keeps record.timerElapsed in sync for the modal display.
  // It is intentionally separate from the store interval — the store drives the sticky widget.
  record.timerInterval = setInterval(() => {
    record.timerElapsed = Math.floor((Date.now() - record.timerStartTime) / 1000)
  }, 500)
}

const showStopTimerConfirm = ref(false)
const recordToStop = ref(null)

const stopTimer = (record) => {
  recordToStop.value = record
  showStopTimerConfirm.value = true
}

const cancelStopTimer = () => {
  showStopTimerConfirm.value = false
  recordToStop.value = null
}

const confirmStopTimer = () => {
  const record = recordToStop.value
  if (!record) return

  if (record.timerInterval) {
    clearInterval(record.timerInterval)
    record.timerInterval = null
  }

  record.timerRunning = false
  record.timer = formatTimerDisplay(record.timerElapsed)
  record.timerElapsed = 0
  record.isNew = false
  isDirty.value = true

  // Release wake lock and reset store only if we own the timer slot
  const anyStillRunning = records.value.some(r => r.timerRunning)
  if (!anyStillRunning) {
    releaseWakeLock()
    if (timerStore.timerOwner === 'student-record') {
      timerStore.resetTimer()
    }
  }
  
  showStopTimerConfirm.value = false
  recordToStop.value = null
  isMinimized.value = false // Always restore modal so user can save or review after stopping
}

const resetTimer = (record) => {
  if (record.timerInterval) {
    clearInterval(record.timerInterval)
  }
  record.timer = ''
  record.timerRunning = false
  record.timerStartTime = null
  record.timerElapsed = 0
  record.timerInterval = null
  record.isNew = true
  isDirty.value = true
}

const formatTimerDisplay = (seconds) => {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60
  
  const parts = []
  if (hours > 0) parts.push(`${hours}h`)
  if (minutes > 0) parts.push(`${minutes}m`)
  if (secs > 0 || parts.length === 0) parts.push(`${secs}s`)
  
  return parts.join(' ')
}

// Format a saved_at timestamp as a short time string (e.g. "10:32 AM")
const formatSavedTime = (savedAt) => {
  if (!savedAt) return ''
  try {
    return new Date(savedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  } catch {
    return ''
  }
}

// ─── Timer Edit Request helpers (tutor only) ────────────────────────────────
const timerEditRequests = ref([])
const editingTimerRecordId = ref(null)
const timerEditHours = ref(0)
const timerEditMinutes = ref(0)
const timerEditSeconds = ref(0)

const loadTimerEditRequests = async () => {
  if (props.apiBase !== '/api/tutor' || !props.studentId) return
  try {
    const response = await axios.get(`${props.apiBase}/timer-edit-requests`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    if (response.data.success && Array.isArray(response.data.data)) {
      // Use == for loose comparison (student_id may come as int or string)
      const filtered = response.data.data.filter(
        r => String(r.student_id) === String(props.studentId)
      )
      timerEditRequests.value = filtered
    }
  } catch (error) {
    // Don't clear existing data on error — keep showing cached badges
    console.error('Failed to load timer edit requests:', error)
  }
}

// Get the most recent pending request for a record
const getTimerPendingRequest = (record) => {
  if (!record.id || !timerEditRequests.value.length) return null
  const recordId = String(record.id)
  return timerEditRequests.value.find(
    r => String(r.record_id) === recordId && r.status === 'pending'
  )
}

// Get the most recent edit request (any status) for a record
const getTimerLatestRequest = (record) => {
  if (!record.id || !timerEditRequests.value.length) return null
  const recordId = String(record.id)
  // timerEditRequests are already sorted by created_at desc from backend
  return timerEditRequests.value.find(
    r => String(r.record_id) === recordId
  )
}

// Count total edit requests for a record (max 2 allowed)
const getTimerEditCount = (record) => {
  if (!record.id || !timerEditRequests.value.length) return 0
  const recordId = String(record.id)
  return timerEditRequests.value.filter(
    r => String(r.record_id) === recordId
  ).length
}

const canEditTimer = (record) => {
  // Block if any request exists (pending, approved, or rejected) — one chance only
  if (getTimerLatestRequest(record)) return false
  return true
}

const parseTimerToHMS = (timerStr) => {
  if (!timerStr || typeof timerStr !== 'string') return { h: 0, m: 0, s: 0 }
  const hours = timerStr.match(/(\d+)h/)
  const minutes = timerStr.match(/(\d+)m/)
  const seconds = timerStr.match(/(\d+)s/)
  return {
    h: hours ? parseInt(hours[1]) : 0,
    m: minutes ? parseInt(minutes[1]) : 0,
    s: seconds ? parseInt(seconds[1]) : 0,
  }
}

const openTimerEditor = (record) => {
  const hms = parseTimerToHMS(record.timer)
  timerEditHours.value = hms.h
  timerEditMinutes.value = hms.m
  timerEditSeconds.value = hms.s
  editingTimerRecordId.value = record.id
}

const cancelTimerEditor = () => {
  editingTimerRecordId.value = null
}

const submitTimerEdit = async (record) => {
  if (!record.id) {
    toast.error('Record must be saved before editing timer')
    return
  }

  const h = timerEditHours.value || 0
  const m = timerEditMinutes.value || 0
  const s = timerEditSeconds.value || 0

  // Build formatted timer string
  const parts = []
  if (h > 0) parts.push(`${h}h`)
  if (m > 0) parts.push(`${m}m`)
  if (s > 0 || parts.length === 0) parts.push(`${s}s`)
  const newTimer = parts.join(' ')

  // Don't submit if no change
  if (newTimer === record.timer) {
    editingTimerRecordId.value = null
    return
  }

  try {
    const response = await axios.post(
      `${props.apiBase}/timer-edit-request`,
      {
        student_id: props.studentId,
        record_id: record.id,
        record_date: record.date,
        old_timer: record.timer || null,
        new_timer: newTimer,
      },
      {
        headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
      }
    )
    if (response.data.success) {
      toast.success('Timer edit request sent for admin approval')
      editingTimerRecordId.value = null
      await loadTimerEditRequests()
    }
  } catch (error) {
    console.error('Failed to submit timer edit request:', error)
    toast.error(error.response?.data?.message || 'Failed to submit timer edit request')
  }
}

// Watch for modal show/hide to add/remove ESC listener
watch(() => props.show, (newVal) => {
  if (newVal) {
    window.addEventListener('keydown', handleEscKey)
  } else {
    window.removeEventListener('keydown', handleEscKey)
  }
})

// Cleanup on unmount
onUnmounted(() => {
  window.removeEventListener('keydown', handleEscKey)
})

const loadRecords = async () => {
  if (!props.studentId) return

  loading.value = true
  try {
    const response = await axios.get(
      `${props.apiBase}/students/${props.studentId}/records`,
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    
    if (response.data && response.data.data) {
      const recordsData = response.data.data
      if (Array.isArray(recordsData) && recordsData.length > 0) {
        records.value = recordsData.map(record => ({
          id: record.id || null,
          date: record.date || '',
          attendance: record.attendance || '--',
          reason: record.reason || '',
          reschedule: record.reschedule || '',
          homework: record.homework || 'Not Given',
          timer: record.timer || '',
          timerRunning: false,
          timerStartTime: null,
          timerElapsed: 0,
          timerInterval: null,
          notes: record.notes || '',
          savedAt: record.saved_at || null,
          isNew: false,
        }))
      } else {
        records.value = []
      }

      // After loading API records, check for an orphaned store timer — this happens
      // when the user minimized a running timer, navigated away, and returned via
      // "Go to Session". The new unsaved record was lost on unmount but the store
      // timer kept ticking. Re-attach the timer to avoid a duplicate date row.
      if (
        timerStore.isTimerRunning &&
        timerStore.timerOwner === 'student-record' &&
        timerStore.timerStudentId === props.studentId &&
        !records.value.some(r => r.timerRunning)
      ) {
        const startedAt = timerStore.sessionTimerStartedAt || Date.now()
        // Use LOCAL date (not UTC) so users in UTC+ timezones get the correct date
        const d = new Date()
        const today = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`

        // Prefer attaching to an existing saved record for today that has no timer
        // set yet — this avoids a duplicate date row entirely.
        const existingToday = records.value.find(
          r => r.date === today && (!r.timer || r.timer.trim() === '') && !r.timerRunning
        )

        let targetRecord
        if (existingToday) {
          // Re-use the existing row — just flip it into running state
          existingToday.timerRunning = true
          existingToday.timerStartTime = startedAt
          existingToday.timerElapsed = Math.floor((Date.now() - startedAt) / 1000)
          existingToday.timerInterval = null
          targetRecord = existingToday
        } else {
          // No suitable existing row — push to END of records.value so that after
          // sortedRecords reverses the array this new row appears at the TOP (newest first).
          records.value.push({
            id: null,
            date: today,
            attendance: 'Present',
            reason: '',
            reschedule: '',
            homework: 'Not Given',
            timer: '',
            timerRunning: true,
            timerStartTime: startedAt,
            timerElapsed: Math.floor((Date.now() - startedAt) / 1000),
            timerInterval: null,
            notes: '',
            isNew: true,
          })
          targetRecord = records.value[records.value.length - 1]
        }

        // IMPORTANT: use the reactive proxy (not the plain object) so Vue
        // observes every tick and the UI updates correctly.
        targetRecord.timerInterval = setInterval(() => {
          targetRecord.timerElapsed = Math.floor((Date.now() - targetRecord.timerStartTime) / 1000)
        }, 500)
        isDirty.value = true
      }
    }
  } catch (error) {
    console.error('Failed to load records:', error)
    if (error.response?.status === 500 && error.response?.data?.message?.includes('table not found')) {
      toast.error('Database table not found. Please run migrations: php artisan migrate')
    }
    records.value = []
  } finally {
    // Load timer edit requests BEFORE hiding spinner so badges render with records
    try { await loadTimerEditRequests() } catch (_) {}
    loading.value = false
  }
}

const loadSyllabus = async () => {
  if (!props.studentId) return

  loading.value = true
  try {
    const response = await axios.get(`${props.apiBase}/students/${props.studentId}/syllabus`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    if (response.data.success) {
      const data = response.data.data || []
      
      // Always start with the full default syllabus structure
      const fullSyllabus = defaultSyllabus.map(item => ({ ...item }))
      
      if (Array.isArray(data) && data.length > 0) {
        // Merge existing data into the full structure
        data.forEach(item => {
          const topicIndex = fullSyllabus.findIndex(
            t => t.level === item.level && t.topic === item.topic
          )
          if (topicIndex !== -1) {
            fullSyllabus[topicIndex].status = item.status || '--'
            fullSyllabus[topicIndex].date = item.date || ''
          }
        })
      }
      
      syllabus.value = fullSyllabus
    } else {
      syllabus.value = defaultSyllabus.map(item => ({ ...item }))
    }
  } catch (error) {
    console.error('Failed to load syllabus:', error)
    if (error.response?.status === 500 && error.response?.data?.message?.includes('table not found')) {
      toast.error('Database table not found. Please run migrations: php artisan migrate')
    }
    // Use default syllabus if error occurs
    syllabus.value = [...defaultSyllabus]
  } finally {
    loading.value = false
  }
}

const saveRecords = async () => {
  if (!props.studentId) return false

  // Auto-stop any running timer and capture elapsed time before saving
  autoStopRunningTimers()

  // Only block save when a NEW (unsaved) row still has no attendance set
  const hasIncompleteNewRecords = records.value.some(r => r.isNew && r.attendance === '--')
  if (hasIncompleteNewRecords) {
    toast.error('Please select Attendance for all new records before saving.')
    return false
  }

  saving.value = true
  try {
    const recordsToSave = records.value.map(({ timerRunning, timerStartTime, timerElapsed, timerInterval, isNew, savedAt, ...record }) => ({
      id: record.id || null,           // backend assigns stable ID when null
      saved_at: savedAt || null,       // backend preserves existing timestamp
      date: record.date || null,
      attendance: record.attendance,
      reason: record.reason || null,
      reschedule: record.reschedule || null,
      homework: record.homework,
      timer: record.timer || null,
      notes: record.notes || null
    }))
    
    const response = await axios.post(
      `${props.apiBase}/students/${props.studentId}/records`,
      { records: recordsToSave },
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    
    if (response.data.success) {
      toast.success('Records saved successfully')
      isDirty.value = false
      // Reload records to get server data
      await loadRecords()
      return true
    }
    return false
  } catch (error) {
    console.error('Failed to save records:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save records'
    toast.error(errorMessage)
    
    if (errorMessage.includes('table not found')) {
      toast.error('Please run: php artisan migrate')
    }
    return false
  } finally {
    saving.value = false
  }
}

const saveSyllabus = async () => {
  if (!props.studentId) return false

  // Auto-stop any running timer before saving
  autoStopRunningTimers()

  if (syllabus.value.length === 0) {
    toast.info('No syllabus data to save')
    return false
  }
  
  saving.value = true
  try {
    // Only send rows that have been explicitly set — backend rejects '--' (untouched default)
    const syllabusToSave = syllabus.value
      .filter(item => item.status === 'Completed' || item.status === 'In Progress')
      .map(item => ({
        level: item.level || 'A1',
        topic: item.topic || '',
        date: item.date || null,
        status: item.status
      }))
    
    const response = await axios.post(
      `${props.apiBase}/students/${props.studentId}/syllabus`,
      { syllabus: syllabusToSave },
      {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      }
    )
    
    if (response.data.success) {
      toast.success('Syllabus saved successfully')
      isDirty.value = false
      return true
    }
    return false
  } catch (error) {
    console.error('Failed to save syllabus:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save syllabus'
    toast.error(errorMessage)
    
    if (errorMessage.includes('table not found')) {
      toast.error('Please run: php artisan migrate')
    }
    return false
  } finally {
    saving.value = false
  }
}

// ─── Sticky widget methods ────────────────────────────────────────────────────────
const minimizeToSticky = () => {
  isMinimized.value = true
}

const restoreFromSticky = () => {
  isMinimized.value = false
}

const stopTimerFromSticky = () => {
  const record = runningTimerRecord.value
  if (record) {
    stopTimer(record)
  } else if (timerStore.isTimerRunning && timerStore.timerOwner === 'student-record') {
    // Store timer is live but no record row holds it (modal was reopened with fresh API data).
    // Just reset the store so the widget disappears cleanly.
    timerStore.resetTimer()
    isMinimized.value = false
  }
}

const handleCloseAttempt = () => {
  // If a timer is running — either in a record row OR in the store (orphaned after
  // "Go to Session" reloads fresh records) — minimize instead of closing.
  if (anyTimerRunning.value || (timerStore.isTimerRunning && timerStore.timerOwner === 'student-record')) {
    minimizeToSticky()
    return
  }

  // Auto-prune blank new records so they never block closure
  records.value = records.value.filter(r => !r.isNew || r.attendance !== '--')

  if (isDirty.value) {
    showUnsavedConfirm.value = true
  } else {
    closeModal()
  }
}

const confirmClose = () => {
  isDirty.value = false
  showUnsavedConfirm.value = false
  closeModal()
}

const saveAndClose = async () => {
  showUnsavedConfirm.value = false
  
  let success = false
  // Save based on active tab
  if (activeTab.value === 'records') {
    success = await saveRecords()
  } else if (activeTab.value === 'syllabus') {
    success = await saveSyllabus()
  }
  
  // Close modal only if saving was successful
  if (success && !saving.value) {
    closeModal()
  }
}

// Global ESC key handler
const handleEscKey = (event) => {
  if (event.key === 'Escape') {
    if (showUnsavedConfirm.value) {
      showUnsavedConfirm.value = false
    } else if (props.show) {
      handleCloseAttempt()
    }
  }
}

const closeModal = () => {
  // Auto-stop any running timers and capture time before closing
  autoStopRunningTimers()

  // Clear all timer intervals
  records.value.forEach(record => {
    if (record.timerInterval) {
      clearInterval(record.timerInterval)
    }
  })

  isMinimized.value = false
  releaseWakeLock()
  emit('close')
}

onUnmounted(() => {
  releaseWakeLock()
  records.value.forEach(r => { if (r.timerInterval) clearInterval(r.timerInterval) })
})

watch(() => props.show, (newVal) => {
  if (newVal && props.studentId) {
    isMinimized.value = false // always start expanded
    isDirty.value = false     // reset dirty flag on every fresh open
    timerEditRequests.value = [] // clear so loadRecords fetches fresh
    loadRecords()
    loadSyllabus()
  }
})

const isFirstInLevel = (level, index) => {
  if (index === 0) return true
  return syllabus.value[index - 1].level !== level
}

const getLevelRowspan = (level, index) => {
  if (!isFirstInLevel(level, index)) return 0
  let count = 1
  for (let i = index + 1; i < syllabus.value.length; i++) {
    if (syllabus.value[i].level === level) {
      count++
    } else {
      break
    }
  }
  return count
}

watch(() => props.studentId, (newVal) => {
  if (newVal && props.show) {
    // If a timer is currently running, do NOT reload records — that would wipe
    // the live timer state. Instead restore the modal so the user can see the
    // running session before switching to a different student.
    if (anyTimerRunning.value) {
      isMinimized.value = false
      return
    }
    loadRecords()
    loadSyllabus()
  }
})
</script>

<style scoped>
/* Additional styling if needed */
</style>

