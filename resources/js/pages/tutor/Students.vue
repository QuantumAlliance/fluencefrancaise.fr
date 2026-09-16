<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Students</h1>
      <div class="flex gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search students..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
        />
        <button
          @click="openCreateGroupModal"
          :disabled="!canCreateGroup"
          :title="!canCreateGroup ? 'You can create a maximum of 5 groups' : 'Create a new group'"
          class="px-6 py-2 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors"
        >
          <i class="fas fa-users mr-2"></i>Create Group
        </button>
        <button
          @click="showAddModal = true"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          + Add Student
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="bg-white rounded-lg shadow p-12 text-center">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading students...</p>
    </div>

    <!-- Students Table -->
    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Group</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-if="filteredStudents.length === 0">
            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
              No students found
            </td>
          </tr>
          <tr
            v-for="student in filteredStudents"
            :key="student.id"
            class="hover:bg-gray-50"
          >
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#0055A4]/20 flex items-center justify-center flex-shrink-0">
                  <span class="text-sm font-bold text-[#0055A4]">
                    {{ getInitials(student) }}
                  </span>
                </div>
                <div class="flex flex-col">
                  <span class="font-medium text-gray-800">{{ student.name }}</span>
                  <span class="text-sm text-gray-500">{{ student.email }}</span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span 
                v-if="getStudentGroup(student.id)"
                class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800"
              >
                {{ getStudentGroup(student.id) }}
              </span>
              <span v-else class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">1 Student</span>
            </td>
            <td class="px-6 py-4">
              <span
                :class="student.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ student.status }}
              </span>
            </td>
            <td class="px-6 py-4 space-x-2">
              <button
                @click="openRecords(student)"
                class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white rounded text-sm transition-colors"
              >
                Records
              </button>
              <button
                @click="openExamPrepAccess(student)"
                :class="examPrepAccessButtonClass(student.id)"
                class="px-3 py-1 rounded text-sm transition-colors inline-flex items-center gap-1.5"
              >
                <svg v-if="getGrantedCount(student.id) > 0" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ examPrepAccessButtonLabel(student.id) }}
              </button>
              <button
                @click="removeStudent(student.id, student.name)"
                :disabled="removingStudent === student.id"
                class="px-3 py-1 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded text-sm transition-colors"
              >
                {{ removingStudent === student.id ? 'Removing...' : 'Remove' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Groups Section -->
    <div class="mt-8">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">My Groups</h2>
        <span class="text-sm text-gray-500">{{ groups.length }}/5 groups</span>
      </div>

      <!-- Groups Loading -->
      <div v-if="loadingGroups" class="bg-white rounded-lg shadow p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
        <p class="mt-2 text-gray-500">Loading groups...</p>
      </div>

      <!-- Groups Grid -->
      <div v-else-if="groups.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="group in groups"
          :key="group.id"
          class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-200 transition-all duration-300 overflow-hidden flex flex-col"
        >
          <!-- Card Header & Metadata -->
          <div class="p-5 flex-1">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors">
                  {{ group.name }}
                </h3>
                <div class="flex items-center gap-2 mt-1 py-0.5 px-2 bg-purple-50 rounded-full w-fit">
                  <i class="fas fa-users text-[10px] text-purple-600"></i>
                  <span class="text-xs font-semibold text-purple-700 uppercase tracking-wider">
                    {{ group.student_count }} Students
                  </span>
                </div>
              </div>
              <div class="flex gap-1">
                <button
                  @click="openEditGroupModal(group)"
                  class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-all"
                  title="Edit Group"
                >
                  <i class="fas fa-edit text-sm"></i>
                </button>
                <button
                  @click="confirmDeleteGroup(group)"
                  class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all"
                  title="Delete Group"
                >
                  <i class="fas fa-trash text-sm"></i>
                </button>
              </div>
            </div>

            <!-- Student Avatars/Tags -->
            <div class="space-y-3">
              <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Enrolled Students</label>
              <div class="flex flex-wrap gap-2">
                <div
                  v-for="student in group.students.slice(0, 3)"
                  :key="student.id"
                  class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 border border-gray-100 text-gray-700 text-xs rounded-lg font-medium"
                >
                  <div class="w-4 h-4 rounded-full bg-purple-100 flex items-center justify-center text-[10px] text-purple-600 font-bold">
                    {{ student.name.charAt(0) }}
                  </div>
                  {{ student.name }}
                </div>
                <div
                  v-if="group.students.length > 3"
                  class="flex items-center px-2.5 py-1.5 bg-purple-100 text-purple-700 text-[11px] rounded-lg font-bold"
                >
                  +{{ group.students.length - 3 }} more
                </div>
              </div>
            </div>
          </div>

          <!-- Card Actions Footer -->
          <div class="px-5 py-4 bg-gray-50/50 border-t border-gray-50">
            <button
              @click="openManageGroupModal(group)"
              class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white hover:bg-green-700 rounded-lg text-sm font-bold transition-all active:scale-[0.98]"
            >
              <i class="fas fa-cog"></i>
              Manage Group
            </button>
          </div>
        </div>
      </div>

      <!-- No Groups -->
      <div v-else class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">No groups created yet</p>
        <p class="text-sm text-gray-400 mt-1">Create a group to organize your students</p>
      </div>
    </div>

    <!-- Create/Edit Group Modal -->
    <div
      v-if="showGroupModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeGroupModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">
              {{ editingGroup ? 'Edit Group' : 'Create Group' }}
            </h3>
            <button
              @click="closeGroupModal"
              class="text-gray-500 hover:text-gray-700"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
          <!-- Group Name -->
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Group Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="groupForm.name"
              type="text"
              placeholder="Enter group name..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
              :class="{ 'border-red-500': groupErrors.name }"
            />
            <p v-if="groupErrors.name" class="text-red-500 text-xs mt-1">{{ groupErrors.name }}</p>
          </div>

          <!-- Student Selection -->
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Select Students <span class="text-red-500">*</span>
            </label>
            <p class="text-xs text-gray-500 mb-2">Select at least 1 student for the group</p>

            <!-- Search Students -->
            <input
              v-model="studentSearchInGroup"
              type="text"
              placeholder="Search students..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 mb-3"
            />

            <!-- Student List with Checkboxes -->
            <div class="border border-gray-200 rounded-lg max-h-48 overflow-y-auto">
              <div
                v-for="student in filteredStudentsForGroup"
                :key="student.id"
                class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 cursor-pointer"
                @click="toggleStudentSelection(student.id)"
              >
                <input
                  type="checkbox"
                  :checked="groupForm.student_ids.includes(student.id)"
                  class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                  @click.stop
                  @change="toggleStudentSelection(student.id)"
                />
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-800">{{ student.name }}</p>
                  <p class="text-xs text-gray-500">{{ student.email }}</p>
                </div>
              </div>
              <div v-if="filteredStudentsForGroup.length === 0" class="p-4 text-center text-gray-500 text-sm">
                No students found
              </div>
            </div>
            <p v-if="groupErrors.students" class="text-red-500 text-xs mt-1">{{ groupErrors.students }}</p>
            <p class="text-xs text-gray-500 mt-2">
              {{ groupForm.student_ids.length }} student{{ groupForm.student_ids.length !== 1 ? 's' : '' }} selected
            </p>
          </div>
        </div>

        <div class="p-6 border-t bg-gray-50">
          <div class="flex gap-3 justify-end">
            <button
              @click="closeGroupModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="saveGroup"
              :disabled="savingGroup"
              class="px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors"
            >
              {{ savingGroup ? 'Saving...' : (editingGroup ? 'Update Group' : 'Create Group') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Group Confirmation Modal -->
    <div
      v-if="showDeleteGroupModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeDeleteGroupModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Delete Group</h3>
          <p class="text-gray-600 mb-6">
            Are you sure you want to delete <strong>{{ groupToDelete?.name }}</strong>? This action cannot be undone.
          </p>
          <div class="flex gap-3 justify-end">
            <button
              @click="closeDeleteGroupModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="deleteGroup"
              :disabled="deletingGroup"
              class="px-4 py-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors"
            >
              {{ deletingGroup ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Group Records Modal (Bulk Update) -->
    <div
      v-if="showGroupRecordsModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeGroupRecordsModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b bg-[#0055A4]">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg font-bold text-white">Update Records - {{ selectedGroupForRecords?.name }}</h3>
              <p class="text-white/80 text-sm mt-1">
                Add a record for selected students in this group
              </p>
            </div>
            <button
              @click="closeGroupRecordsModal"
              class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
          <!-- Student Selection -->
          <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center mb-3">
              <h4 class="text-sm font-semibold text-gray-700">Select students to update:</h4>
              <button
                @click="toggleAllRecordsStudents"
                class="text-xs text-[#0055A4] hover:text-[#003d7a] font-medium"
              >
                {{ isAllRecordsStudentsSelected ? 'Deselect All' : 'Select All' }}
              </button>
            </div>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="student in selectedGroupForRecords?.students"
                :key="student.id"
                @click="toggleRecordsStudentSelection(student.id)"
                :class="[
                  'px-3 py-1.5 text-sm rounded-full border transition-all cursor-pointer',
                  groupRecordForm.selectedStudentIds.includes(student.id)
                    ? 'bg-[#0055A4] border-[#0055A4] text-white'
                    : 'bg-white border-gray-300 text-gray-700 hover:border-[#0055A4]'
                ]"
              >
                <i v-if="groupRecordForm.selectedStudentIds.includes(student.id)" class="fas fa-check mr-1 text-xs"></i>
                {{ student.name }}
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">
              <span class="font-medium text-[#0055A4]">{{ groupRecordForm.selectedStudentIds.length }}</span> of {{ selectedGroupForRecords?.students?.length || 0 }} students selected
            </p>
          </div>

          <!-- Record Form -->
          <div class="space-y-4">
            <!-- Date -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">
                Date <span class="text-red-500">*</span>
              </label>
              <input
                v-model="groupRecordForm.date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>

            <!-- Attendance -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Attendance</label>
              <select
                v-model="groupRecordForm.attendance"
                :class="[
                  'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-medium transition-colors',
                  groupRecordForm.attendance === 'Present' ? 'bg-green-50 border-green-300 text-green-800' :
                  groupRecordForm.attendance === 'Absent (Notice Given)' ? 'bg-yellow-50 border-yellow-300 text-yellow-800' :
                  groupRecordForm.attendance === 'Missed (No Notice)' ? 'bg-red-50 border-red-300 text-red-800' :
                  'bg-white border-gray-300 text-gray-700'
                ]"
              >
                <option value="Present">Present</option>
                <option value="Absent (Notice Given)">Absent (Notice Given)</option>
                <option value="Missed (No Notice)">Missed (No Notice)</option>
              </select>
            </div>

            <!-- Reason (if absent) -->
            <div v-if="groupRecordForm.attendance !== 'Present'">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Reason</label>
              <input
                v-model="groupRecordForm.reason"
                type="text"
                placeholder="Enter reason for absence..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>

            <!-- Reschedule Date -->
            <div v-if="groupRecordForm.attendance !== 'Present'">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Reschedule Date</label>
              <input
                v-model="groupRecordForm.reschedule"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>

            <!-- Homework -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Homework</label>
              <select
                v-model="groupRecordForm.homework"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="Done">Done</option>
                <option value="Not Done">Not Done</option>
                <option value="Partial">Partial</option>
              </select>
            </div>

            <!-- Progress -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Progress</label>
              <input
                v-model="groupRecordForm.progress"
                type="text"
                placeholder="Enter progress notes..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Notes</label>
              <textarea
                v-model="groupRecordForm.notes"
                rows="3"
                placeholder="Enter any additional notes..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] resize-none"
              ></textarea>
            </div>
          </div>
        </div>

        <div class="p-6 border-t bg-gray-50">
          <div class="flex gap-3 justify-end">
            <button
              @click="closeGroupRecordsModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="saveGroupRecords"
              :disabled="savingGroupRecords || !groupRecordForm.date"
              class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i v-if="savingGroupRecords" class="fas fa-spinner fa-spin"></i>
              {{ savingGroupRecords ? 'Saving...' : 'Save Records for All Students' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Group Syllabus Modal (Bulk Update) -->
    <div
      v-if="showGroupSyllabusModal"
      class="fixed inset-0 bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white w-full h-full overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex justify-between items-center">
          <div>
            <h2 class="text-xl font-bold text-white">Update Syllabus - {{ selectedGroupForSyllabus?.name }}</h2>
            <p class="text-white/80 text-sm mt-1">
              Update syllabus for all {{ selectedGroupForSyllabus?.student_count }} students in this group
            </p>
          </div>
          <button
            @click="closeGroupSyllabusModal"
            class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Level Filter Tabs -->
        <div class="border-b border-gray-200 px-6">
          <div class="flex gap-2 overflow-x-auto py-3">
            <button
              v-for="level in syllabusLevels"
              :key="level"
              @click="selectedSyllabusLevel = level"
              :class="[
                'px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors',
                selectedSyllabusLevel === level
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              {{ level }}
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6">
          <!-- Loading State -->
          <div v-if="loadingGroupSyllabus" class="flex items-center justify-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading syllabus data...</span>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-12">
                    <input
                      type="checkbox"
                      :checked="isAllTopicsSelected"
                      @change="toggleAllTopics"
                      class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                  </th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Topics</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-40">Status</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 w-44">Date</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in filteredSyllabusItems"
                  :key="getTopicIndex(item)"
                  class="border-b border-gray-200 hover:bg-gray-50"
                  :class="{ 'bg-blue-50': selectedSyllabusTopics.includes(getTopicIndex(item)) }"
                >
                  <td class="px-4 py-3">
                    <input
                      type="checkbox"
                      :checked="selectedSyllabusTopics.includes(getTopicIndex(item))"
                      @change="toggleTopicSelection(getTopicIndex(item))"
                      class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                  </td>
                  <td class="px-4 py-3 text-gray-800">{{ item.topic }}</td>
                  <td class="px-4 py-3">
                    <select
                      v-model="groupSyllabusForm[getTopicIndex(item)].status"
                      :class="[
                        'w-full px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-medium',
                        groupSyllabusForm[getTopicIndex(item)].status === 'Completed' ? 'bg-green-100 border-green-300 text-green-800' :
                        groupSyllabusForm[getTopicIndex(item)].status === 'In Progress' ? 'bg-yellow-100 border-yellow-300 text-yellow-800' :
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
                      v-model="groupSyllabusForm[getTopicIndex(item)].date"
                      type="date"
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
          <div class="text-sm text-gray-600">
            <span class="font-medium">{{ selectedSyllabusTopics.length }}</span> topics selected
          </div>
          <div class="flex gap-3">
            <button
              @click="closeGroupSyllabusModal"
              :disabled="savingGroupSyllabus"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="saveGroupSyllabus"
              :disabled="savingGroupSyllabus"
              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i v-if="savingGroupSyllabus" class="fas fa-spinner fa-spin"></i>
              {{ savingGroupSyllabus ? 'Saving...' : 'Save Syllabus for All Students' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Group Attendance Modal -->
    <div
      v-if="showGroupAttendanceModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeGroupAttendanceModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <div class="p-6 border-b bg-green-600">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg font-bold text-white">Track Attendance - {{ selectedGroupForAttendance?.name }}</h3>
              <p class="text-white/80 text-sm mt-1">
                Mark present students. Others will be marked as absent.
              </p>
            </div>
            <button
              @click="closeGroupAttendanceModal"
              class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
          <!-- Date Selection -->
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Class Date <span class="text-red-500">*</span>
            </label>
            <input
              v-model="attendanceForm.date"
              @change="onAttendanceDateChange"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500"
            />
          </div>

          <!-- Notes -->
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Notes
            </label>
            <textarea
              v-model="attendanceForm.notes"
              rows="3"
              placeholder="Enter any additional notes..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 resize-none"
            ></textarea>
          </div>

          <!-- Loading State -->
          <div v-if="loadingGroupAttendance" class="mb-4 flex items-center justify-center py-4">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
            <span class="ml-2 text-gray-600 text-sm">Loading attendance...</span>
          </div>

          <!-- Student Selection -->
          <div v-else class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Student Attendance
            </label>
            <p class="text-xs text-gray-500 mb-3">
              <i class="fas fa-info-circle mr-1"></i>
              Select attendance status for each student
            </p>

            <!-- Mark All Buttons -->
            <div class="flex items-center gap-2 mb-3 pb-3 border-b">
              <span class="text-sm text-gray-600 mr-2">Mark all as:</span>
              <button
                type="button"
                @click="markAllAttendance('Present')"
                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 rounded-full transition-colors"
              >
                Present
              </button>
              <button
                type="button"
                @click="markAllAttendance('Absent (Notice Given)')"
                class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 rounded-full transition-colors"
              >
                Absent (Notice)
              </button>
              <button
                type="button"
                @click="markAllAttendance('Missed (No Notice)')"
                class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 rounded-full transition-colors"
              >
                Missed
              </button>
            </div>

            <!-- Student List -->
            <div class="border border-gray-200 rounded-lg max-h-64 overflow-y-auto">
              <div
                v-for="student in selectedGroupForAttendance?.students"
                :key="student.id"
                class="flex items-center gap-3 p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0"
                :class="{
                  'bg-green-50': attendanceForm.studentAttendance[student.id] === 'Present',
                  'bg-yellow-50': attendanceForm.studentAttendance[student.id] === 'Absent (Notice Given)',
                  'bg-red-50': attendanceForm.studentAttendance[student.id] === 'Missed (No Notice)'
                }"
              >
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                  :class="{
                    'bg-green-200': attendanceForm.studentAttendance[student.id] === 'Present',
                    'bg-yellow-200': attendanceForm.studentAttendance[student.id] === 'Absent (Notice Given)',
                    'bg-red-200': attendanceForm.studentAttendance[student.id] === 'Missed (No Notice)',
                    'bg-gray-200': !attendanceForm.studentAttendance[student.id]
                  }"
                >
                  <span class="text-xs font-bold"
                    :class="{
                      'text-green-700': attendanceForm.studentAttendance[student.id] === 'Present',
                      'text-yellow-700': attendanceForm.studentAttendance[student.id] === 'Absent (Notice Given)',
                      'text-red-700': attendanceForm.studentAttendance[student.id] === 'Missed (No Notice)',
                      'text-gray-600': !attendanceForm.studentAttendance[student.id]
                    }"
                  >
                    {{ getStudentInitials(student.name) }}
                  </span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-800 truncate">{{ student.name }}</p>
                  <p class="text-xs text-gray-500 truncate">{{ student.email }}</p>
                </div>
                <div class="flex-shrink-0">
                  <select
                    v-model="attendanceForm.studentAttendance[student.id]"
                    class="text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500"
                    :class="{
                      'bg-green-100 text-green-800': attendanceForm.studentAttendance[student.id] === 'Present',
                      'bg-yellow-100 text-yellow-800': attendanceForm.studentAttendance[student.id] === 'Absent (Notice Given)',
                      'bg-red-100 text-red-800': attendanceForm.studentAttendance[student.id] === 'Missed (No Notice)'
                    }"
                  >
                    <option v-for="option in attendanceOptions" :key="option" :value="option">{{ option }}</option>
                  </select>
                </div>
              </div>
            </div>

            <p class="text-sm text-gray-600 mt-3">
              <span class="font-medium text-green-600">{{ presentCount }}</span> present,
              <span class="font-medium text-yellow-600">{{ absentNoticeCount }}</span> absent (notice),
              <span class="font-medium text-red-600">{{ missedCount }}</span> missed
            </p>
          </div>
        </div>

        <div class="p-6 border-t bg-gray-50">
          <div class="flex gap-3 justify-end">
            <button
              @click="closeGroupAttendanceModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="saveGroupAttendance"
              :disabled="savingGroupAttendance || !attendanceForm.date"
              class="px-6 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i v-if="savingGroupAttendance" class="fas fa-spinner fa-spin"></i>
              {{ savingGroupAttendance ? 'Saving...' : 'Save Attendance' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Student Modal -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeAddModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Add Student</h3>
            <button
              @click="closeAddModal"
              class="text-gray-500 hover:text-gray-700"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Search Student by Email
            </label>
            <div class="relative">
              <input
                v-model="studentSearchEmail"
                type="email"
                placeholder="Enter student email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                @input="searchStudent"
                :disabled="searchingStudent"
              />
              <div v-if="searchingStudent" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-[#0055A4]"></div>
              </div>
            </div>
            <p v-if="studentSearchError" class="text-red-500 text-xs mt-1">{{ studentSearchError }}</p>
            <p v-if="searchingStudent" class="text-gray-500 text-xs mt-1">Searching...</p>
        </div>

          <div v-if="foundStudent" class="mb-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-800">{{ foundStudent.name }}</p>
            <p class="text-xs text-gray-600">{{ foundStudent.email }}</p>
          </div>

          <div class="flex gap-3 justify-end">
            <button
              @click="closeAddModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="confirmAddStudent"
              :disabled="!foundStudent || addingStudent"
              class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors"
            >
              {{ addingStudent ? 'Adding...' : 'Add Student' }}
            </button>
          </div>
          </div>
          </div>
        </div>

    <!-- Remove Confirmation Modal -->
    <div
      v-if="showRemoveModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeRemoveModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Remove Student</h3>
          <p class="text-gray-600 mb-6">
            Are you sure you want to remove <strong>{{ studentToRemove?.name }}</strong> from your students list?
          </p>
          <div class="flex gap-3 justify-end">
          <button
              @click="closeRemoveModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
          >
              Cancel
          </button>
          <button
              @click="confirmRemove"
              :disabled="removingStudent !== null"
              class="px-4 py-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors"
          >
              {{ removingStudent ? 'Removing...' : 'Remove' }}
          </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Manage Group Modal (Unified with Tabs) -->
    <div
      v-if="showManageGroupModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl shadow-lg border border-gray-200 max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4" :class="manageGroupTab === 'syllabus' ? 'bg-blue-600' : 'bg-green-600'">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-xl font-bold text-white">
                {{ manageGroupTab === 'syllabus' ? 'Update Syllabus' : 'Manage Group' }} - {{ selectedManageGroup?.name }}
              </h3>
              <p class="text-white/80 text-sm mt-1">
                {{ manageGroupTab === 'syllabus' 
                  ? `Update syllabus for all ${selectedManageGroup?.student_count} students in this group` 
                  : `Track attendance and manage syllabus for ${selectedManageGroup?.student_count} students` 
                }}
              </p>
            </div>
            <div class="flex items-center gap-2">
              <!-- Minimize to sticky widget while timer is running -->
              <button
                v-if="sessionTimerRunning"
                @click="closeManageGroupModal"
                class="flex items-center gap-1.5 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                title="Minimize — timer keeps running"
              >
                <i class="fas fa-thumbtack"></i>
                Minimize &amp; Keep Timer
              </button>
              <button
                @click="closeManageGroupModal"
                class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 bg-gray-50">
          <button
            @click="manageGroupTab = 'attendance'"
            :class="[
              'flex items-center gap-2 px-6 py-3 font-medium transition-colors border-b-2',
              manageGroupTab === 'attendance'
                ? 'text-green-600 border-green-600 bg-white'
                : 'text-gray-600 border-transparent hover:text-green-600 hover:bg-gray-100'
            ]"
          >
            <i class="fas fa-check-circle" :class="manageGroupTab === 'attendance' ? 'text-green-600' : 'text-gray-400'"></i>
            Attendance
          </button>
          <button
            @click="manageGroupTab = 'syllabus'"
            :class="[
              'flex items-center gap-2 px-6 py-3 font-medium transition-colors border-b-2',
              manageGroupTab === 'syllabus'
                ? 'text-green-600 border-green-600 bg-white'
                : 'text-gray-600 border-transparent hover:text-green-600 hover:bg-gray-100'
            ]"
          >
            <i class="fas fa-book" :class="manageGroupTab === 'syllabus' ? 'text-green-600' : 'text-gray-400'"></i>
            Syllabus
          </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6">
          <!-- Attendance Tab -->
          <div v-if="manageGroupTab === 'attendance'" class="space-y-6">
            <!-- Class Date -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">
                Class Date
              </label>
              <input
                v-model="manageGroupAttendance.date"
                @change="onManageGroupDateChange"
                type="date"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500"
              />
            </div>

            <!-- Session Timer -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Session Timer</label>
              <div
                class="flex items-center justify-center p-4 rounded-lg border-2 transition-all"
                :class="[
                  sessionTimerStopped 
                    ? 'bg-gray-100 border-gray-300'
                    : sessionTimerRunning 
                      ? 'bg-red-50 border-red-300 hover:bg-red-100 cursor-pointer' 
                      : 'bg-green-50 border-green-300 hover:bg-green-100 cursor-pointer'
                ]"
                @click="toggleSessionTimer"
              >
                <!-- Timer Stopped/Finalized -->
                <template v-if="sessionTimerStopped">
                  <i class="fas fa-check-circle text-green-600 mr-3 text-xl"></i>
                  <span class="font-bold text-2xl text-gray-800 font-mono">{{ formatSessionTimer }}</span>
                  <span class="text-sm text-green-600 ml-2 font-medium">(Session Recorded)</span>
                </template>
                <!-- Timer Running -->
                <template v-else-if="sessionTimerRunning">
                  <i class="fas fa-stop-circle text-red-600 mr-3 text-xl"></i>
                  <span class="font-bold text-2xl text-gray-800 font-mono">{{ formatSessionTimer }}</span>
                </template>
                <!-- Timer Not Started -->
                <template v-else>
                  <i class="fas fa-play-circle text-green-600 mr-3 text-xl"></i>
                  <span class="font-semibold text-green-700">Start Session Timer</span>
                </template>
              </div>
              <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                <i class="fas fa-info-circle"></i>
                Track the total session duration for this class
              </p>
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Notes</label>
              <textarea
                v-model="manageGroupAttendance.notes"
                rows="3"
                placeholder="Enter any additional notes..."
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 resize-none"
              ></textarea>
            </div>

            <!-- Loading State -->
            <div v-if="loadingManageGroupData" class="flex items-center justify-center py-4">
              <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
              <span class="ml-2 text-gray-600 text-sm">Loading attendance data...</span>
            </div>

            <!-- Student Attendance -->
            <div v-else>
              <div class="flex justify-between items-center mb-3">
                <div>
                  <label class="block text-gray-700 text-sm font-semibold">Student Attendance</label>
                  <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Select attendance status for each student
                  </p>
                </div>
              </div>

              <!-- Mark All Buttons -->
              <div class="flex items-center gap-2 mb-4">
                <span class="text-sm text-gray-600 mr-2">Mark all as:</span>
                <button
                  @click="markAllManageAttendance('Present')"
                  class="px-3 py-1 text-xs font-semibold rounded-full border border-green-300 bg-green-50 text-green-700 hover:bg-green-100 transition-colors"
                >
                  Present
                </button>
                <button
                  @click="markAllManageAttendance('Absent (Notice Given)')"
                  class="px-3 py-1 text-xs font-semibold rounded-full border border-yellow-300 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition-colors"
                >
                  Absent (Notice)
                </button>
                <button
                  @click="markAllManageAttendance('Missed (No Notice)')"
                  class="px-3 py-1 text-xs font-semibold rounded-full border border-red-300 bg-red-50 text-red-700 hover:bg-red-100 transition-colors"
                >
                  Missed
                </button>
              </div>

              <!-- Student List -->
              <div class="space-y-2 max-h-64 overflow-y-auto">
                <div
                  v-for="student in selectedManageGroup?.students"
                  :key="student.id"
                  class="flex items-center gap-3 p-3 rounded-lg border transition-all"
                  :class="{
                    'bg-green-50 border-green-200': manageGroupAttendance.studentStatuses[student.id] === 'Present',
                    'bg-yellow-50 border-yellow-200': manageGroupAttendance.studentStatuses[student.id] === 'Absent (Notice Given)',
                    'bg-red-50 border-red-200': manageGroupAttendance.studentStatuses[student.id] === 'Missed (No Notice)',
                    'bg-gray-50 border-gray-200': !manageGroupAttendance.studentStatuses[student.id]
                  }"
                >
                  <div
                    class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                    :class="{
                      'bg-green-200': manageGroupAttendance.studentStatuses[student.id] === 'Present',
                      'bg-yellow-200': manageGroupAttendance.studentStatuses[student.id] === 'Absent (Notice Given)',
                      'bg-red-200': manageGroupAttendance.studentStatuses[student.id] === 'Missed (No Notice)',
                      'bg-gray-200': !manageGroupAttendance.studentStatuses[student.id]
                    }"
                  >
                    <span class="text-xs font-bold"
                      :class="{
                        'text-green-700': manageGroupAttendance.studentStatuses[student.id] === 'Present',
                        'text-yellow-700': manageGroupAttendance.studentStatuses[student.id] === 'Absent (Notice Given)',
                        'text-red-700': manageGroupAttendance.studentStatuses[student.id] === 'Missed (No Notice)',
                        'text-gray-600': !manageGroupAttendance.studentStatuses[student.id]
                      }"
                    >
                      {{ getStudentInitials(student.name) }}
                    </span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ student.name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ student.email }}</p>
                  </div>
                  <div class="flex flex-col gap-2 flex-shrink-0 w-48">
                    <select
                      v-model="manageGroupAttendance.studentStatuses[student.id]"
                      class="text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 w-full"
                      :class="{
                        'bg-green-100 text-green-800 border-green-300': manageGroupAttendance.studentStatuses[student.id] === 'Present',
                        'bg-yellow-100 text-yellow-800 border-yellow-300': manageGroupAttendance.studentStatuses[student.id] === 'Absent (Notice Given)',
                        'bg-red-100 text-red-800 border-red-300': manageGroupAttendance.studentStatuses[student.id] === 'Missed (No Notice)'
                      }"
                    >
                      <option value="--">--</option>
                      <option value="Present">Present</option>
                      <option value="Absent (Notice Given)">Absent (Notice Given)</option>
                      <option value="Missed (No Notice)">Missed (No Notice)</option>
                    </select>
                    
                    <!-- Dynamic Reason Field for Absent/Missed -->
                    <input
                      v-if="manageGroupAttendance.studentStatuses[student.id] === 'Absent (Notice Given)' || manageGroupAttendance.studentStatuses[student.id] === 'Missed (No Notice)'"
                      v-model="manageGroupAttendance.studentReasons[student.id]"
                      type="text"
                      placeholder="Enter reason..."
                      class="text-xs px-2 py-1.5 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500 w-full"
                    />
                  </div>
                </div>
              </div>

              <!-- Attendance Summary -->
              <p class="text-sm text-gray-600 mt-4">
                <span class="font-medium text-green-600">{{ manageGroupPresentCount }}</span> present,
                <span class="font-medium text-yellow-600">{{ manageGroupAbsentNoticeCount }}</span> absent (notice),
                <span class="font-medium text-red-600">{{ manageGroupMissedCount }}</span> missed
              </p>
            </div>
          </div>

          <!-- Syllabus Tab -->
          <div v-if="manageGroupTab === 'syllabus'" class="-mx-6 -mt-6 flex flex-col h-full">
            <!-- Level Tabs -->
            <div class="px-6 py-3 bg-white border-b border-gray-200 flex gap-2">
              <button
                v-for="level in syllabusLevels"
                :key="level"
                @click="manageGroupSyllabusLevel = level"
                :class="[
                  'px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-all',
                  manageGroupSyllabusLevel === level
                    ? 'bg-blue-600 text-white shadow-md'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                ]"
              >
                {{ level }}
              </button>
            </div>

            <!-- Table Header -->
            <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
              <div class="col-span-1 flex items-center">
                <input
                  type="checkbox"
                  :checked="isAllManageGroupTopicsSelected"
                  @change="toggleAllManageGroupTopics"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
              </div>
              <div class="col-span-7">Topics</div>
              <div class="col-span-2 text-center">Status</div>
              <div class="col-span-2 text-center">Date</div>
            </div>

            <!-- Topics List -->
            <div class="flex-1 overflow-y-auto">
              <div
                v-for="(topic, idx) in filteredManageGroupSyllabusTopics"
                :key="idx"
                class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors items-center"
              >
                <!-- Checkbox -->
                <div class="col-span-1">
                  <input
                    type="checkbox"
                    :checked="manageGroupSelectedTopics.includes(getManageGroupTopicIndex(topic))"
                    @change="toggleManageGroupTopic(getManageGroupTopicIndex(topic))"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  />
                </div>

                <!-- Topic Name -->
                <div class="col-span-7">
                  <span class="text-sm text-gray-800">{{ topic.topic }}</span>
                </div>

                <!-- Status Dropdown -->
                <div class="col-span-2">
                  <select
                    v-model="manageGroupSyllabusForm[getManageGroupTopicIndex(topic)].status"
                    class="w-full text-xs border rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    :class="{
                      'bg-green-100 text-green-800 border-green-300': manageGroupSyllabusForm[getManageGroupTopicIndex(topic)]?.status === 'Completed',
                      'bg-yellow-100 text-yellow-800 border-yellow-300': manageGroupSyllabusForm[getManageGroupTopicIndex(topic)]?.status === 'In Progress',
                      'bg-gray-100 text-gray-600 border-gray-300': !manageGroupSyllabusForm[getManageGroupTopicIndex(topic)]?.status || manageGroupSyllabusForm[getManageGroupTopicIndex(topic)]?.status === '--'
                    }"
                  >
                    <option value="--">--</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                  </select>
                </div>

                <!-- Date Picker -->
                <div class="col-span-2">
                  <input
                    v-model="manageGroupSyllabusForm[getManageGroupTopicIndex(topic)].date"
                    type="date"
                    class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    placeholder="dd-mm-yyyy"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t bg-gray-50 flex justify-between items-center">
          <p class="text-sm text-gray-600">
            <template v-if="manageGroupTab === 'syllabus'">
              <span class="font-medium text-blue-600">{{ manageGroupSelectedTopics.length }}</span> topics selected
            </template>
            <template v-else>
              {{ selectedManageGroup?.student_count }} students in this group
            </template>
          </p>
          <div class="flex gap-3">
            <button
              @click="closeManageGroupModal"
              class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              v-if="manageGroupTab === 'attendance'"
              @click="saveManageGroupAttendance"
              :disabled="savingManageGroup || !manageGroupAttendance.date"
              class="px-6 py-2 bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i v-if="savingManageGroup" class="fas fa-spinner fa-spin"></i>
              {{ savingManageGroup ? 'Saving...' : 'Save Attendance' }}
            </button>
            <button
              v-if="manageGroupTab === 'syllabus'"
              @click="saveManageGroupSyllabus"
              :disabled="savingManageGroup || manageGroupSelectedTopics.length === 0"
              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <i v-if="savingManageGroup" class="fas fa-spinner fa-spin"></i>
              {{ savingManageGroup ? 'Saving...' : 'Save Syllabus for All Students' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stop Timer Confirmation Dialog -->
    <div
      v-if="showStopTimerConfirm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]"
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
              <p class="text-white/80 text-sm">Current session: {{ formatSessionTimer }}</p>
            </div>
          </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
          <p class="text-gray-600 mb-4">
            Are you sure you want to stop the session timer? The recorded time will be saved with the attendance.
          </p>
          <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
            <span class="text-3xl font-bold text-gray-800 font-mono">{{ formatSessionTimer }}</span>
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

    <!-- Timer Conflict Dialog (student-record timer running while trying to start group timer) -->
    <div
      v-if="showTimerConflictDialog"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[80]"
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
          <p class="text-gray-600">A student session timer is currently running. Please stop it before starting a group session timer.</p>
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

    <!-- Unsaved Changes Confirmation (Group Modal) -->
    <div
      v-if="showGroupUnsavedConfirm"
      class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center"
      @click.self="showGroupUnsavedConfirm = false"
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
            @click="showGroupUnsavedConfirm = false"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-sm"
          >
            Keep Editing
          </button>
          <div class="flex items-center gap-2">
            <button
              @click="confirmGroupCloseWithoutSaving"
              class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors flex items-center gap-1.5 text-sm"
            >
              <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Discard
            </button>
            <button
              @click="confirmGroupSaveAndClose"
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

    <!-- Student Records Modal -->
    <StudentRecordsModal
      :show="showRecordsModal"
      :student-id="selectedStudentId"
      :student-name="selectedStudentName"
      :is-group-timer-sticky="timerIsSticky"
      api-base="/api/tutor"
      @close="closeRecordsModal"
    />

    <!-- ───────────────── Sticky Floating Timer Widget ───────────────── -->
    <!-- Shown when the Manage Group modal is closed but the timer is still running -->
    <transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-4 scale-95"
    >
      <div
        v-if="timerIsSticky && timerStore.timerOwner === 'group'"
        class="fixed bottom-6 right-6 z-[70] select-none"
      >
        <!-- Widget card -->
        <div class="bg-gray-900 text-white rounded-2xl shadow-2xl border border-white/10 overflow-hidden min-w-[260px]">
          <!-- Top bar: group name + pulse dot -->
          <div class="flex items-center gap-2 px-4 pt-3 pb-1">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-xs font-semibold text-green-400 uppercase tracking-widest">Session Running</span>
          </div>

          <div class="px-4 pb-1">
            <p class="text-xs text-gray-400 truncate max-w-[200px]">
              <i class="fas fa-users mr-1"></i>{{ selectedManageGroup?.name }}
            </p>
          </div>

          <!-- Timer display -->
          <div class="px-4 py-2 flex items-center justify-center">
            <span class="text-4xl font-mono font-bold tracking-tight text-white">
              {{ formatSessionTimer }}
            </span>
          </div>

          <!-- Hint -->
          <div class="px-4 pb-2 text-center">
            <p class="text-[10px] text-gray-500">
              <i class="fas fa-lock-open mr-1"></i>Screen will stay awake
            </p>
          </div>

          <!-- Action buttons -->
          <div class="flex border-t border-white/10">
            <button
              @click="reopenModalFromSticky"
              class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-colors"
              title="Reopen modal"
            >
              <i class="fas fa-expand-alt text-xs"></i>
              Reopen
            </button>
            <div class="w-px bg-white/10"></div>
            <button
              @click="toggleSessionTimer"
              class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors"
              title="Stop timer"
            >
              <i class="fas fa-stop-circle text-xs"></i>
              Stop
            </button>
          </div>
        </div>
      </div>
    </transition>

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
              <p class="text-sm text-gray-600 mt-1">{{ examPrepModalStudent?.name }}</p>
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

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useToast } from '../../composables/useToast'
import axios from 'axios'
import StudentRecordsModal from '../../components/StudentRecordsModal.vue'
import { useTimerStore } from '../../stores/timer'

const toast = useToast()
const timerStore = useTimerStore()
const loading = ref(true)
const removingStudent = ref(null)

// Exam Prep Access modal state
const examPrepModalOpen = ref(false)
const examPrepModalStudent = ref(null)
const examPrepList = ref([])
const examPrepLoading = ref(false)
const examPrepBulk = ref(null)
const examPrepRowSaving = ref({})

// Per-student access summary { studentId: grantedCount }
const examPrepAccessSummary = ref({})
const examPrepTotalCount = ref(0)

const fetchExamPrepAccessSummary = async () => {
  try {
    const response = await axios.get('/api/tutor/exam-prep-access-summary')
    if (response.data.success && response.data.data) {
      examPrepTotalCount.value = response.data.data.total_exam_preps || 0
      const map = {}
      for (const row of (response.data.data.students || [])) {
        map[row.student_id] = row.granted_count
      }
      examPrepAccessSummary.value = map
    }
  } catch (e) {
    // ignore — button stays default
  }
}

const getGrantedCount = (studentId) => {
  return examPrepAccessSummary.value[studentId] || 0
}

const examPrepAccessButtonClass = (studentId) => {
  const granted = getGrantedCount(studentId)
  const total = examPrepTotalCount.value
  if (granted === 0) return 'bg-[#0055A4] hover:bg-[#003d7a] text-white'
  if (granted >= total && total > 0) return 'bg-green-600 hover:bg-green-700 text-white'
  return 'bg-emerald-500 hover:bg-emerald-600 text-white'
}

const examPrepAccessButtonLabel = (studentId) => {
  const granted = getGrantedCount(studentId)
  const total = examPrepTotalCount.value
  if (granted === 0) return 'Exam Prep Access'
  if (total > 0) return `Access ${granted}/${total}`
  return 'Access'
}

const examPrepGrantedCount = computed(() => examPrepList.value.filter(e => e.has_access).length)
const examPrepAllGranted = computed(() => examPrepList.value.length > 0 && examPrepGrantedCount.value === examPrepList.value.length)

const openExamPrepAccess = async (student) => {
  examPrepModalStudent.value = student
  examPrepModalOpen.value = true
  examPrepList.value = []
  examPrepLoading.value = true
  try {
    const response = await axios.get(`/api/tutor/students/${student.id}/exam-prep-access`)
    if (response.data.success) {
      examPrepList.value = response.data.data || []
    }
  } catch (e) {
    examPrepList.value = []
    toast.error('Failed to load exam preps')
  } finally {
    examPrepLoading.value = false
  }
}

const closeExamPrepAccess = () => {
  examPrepModalOpen.value = false
  examPrepModalStudent.value = null
  examPrepList.value = []
  examPrepRowSaving.value = {}
}

const updateSummaryForStudent = (studentId) => {
  const granted = examPrepList.value.filter(e => e.has_access).length
  examPrepAccessSummary.value = { ...examPrepAccessSummary.value, [studentId]: granted }
  if (examPrepList.value.length > examPrepTotalCount.value) {
    examPrepTotalCount.value = examPrepList.value.length
  }
}

const toggleStudentExamPrep = async (ep) => {
  if (!examPrepModalStudent.value) return
  const studentId = examPrepModalStudent.value.id
  const wasGranted = ep.has_access
  examPrepRowSaving.value = { ...examPrepRowSaving.value, [ep.id]: true }
  try {
    if (wasGranted) {
      await axios.delete(`/api/tutor/exam-preps/${ep.id}/access/${studentId}`)
      ep.has_access = false
    } else {
      await axios.post(`/api/tutor/exam-preps/${ep.id}/access`, { student_id: studentId })
      ep.has_access = true
    }
    updateSummaryForStudent(studentId)
  } catch (e) {
    toast.error('Failed to update access')
  } finally {
    const next = { ...examPrepRowSaving.value }
    delete next[ep.id]
    examPrepRowSaving.value = next
  }
}

const grantAllExamPreps = async () => {
  if (!examPrepModalStudent.value) return
  const studentId = examPrepModalStudent.value.id
  examPrepBulk.value = 'grant'
  try {
    await axios.post(`/api/tutor/students/${studentId}/exam-prep-access/grant-all`)
    examPrepList.value = examPrepList.value.map(e => ({ ...e, has_access: true }))
    updateSummaryForStudent(studentId)
    toast.success('Granted access to all exam preps')
  } catch (e) {
    toast.error('Failed to grant access')
  } finally {
    examPrepBulk.value = null
  }
}

const revokeAllExamPreps = async () => {
  if (!examPrepModalStudent.value) return
  const studentId = examPrepModalStudent.value.id
  examPrepBulk.value = 'revoke'
  try {
    await axios.delete(`/api/tutor/students/${studentId}/exam-prep-access/all`)
    examPrepList.value = examPrepList.value.map(e => ({ ...e, has_access: false }))
    updateSummaryForStudent(studentId)
    toast.success('Revoked access to all exam preps')
  } catch (e) {
    toast.error('Failed to revoke access')
  } finally {
    examPrepBulk.value = null
  }
}
const showRemoveModal = ref(false)
const studentToRemove = ref(null)
const showAddModal = ref(false)
const studentSearchEmail = ref('')
const foundStudent = ref(null)
const studentSearchError = ref('')
const addingStudent = ref(false)
const searchingStudent = ref(false)

const searchQuery = ref('')
const students = ref([])
const showRecordsModal = ref(false)
const selectedStudentId = ref(null)
const selectedStudentName = ref('')

// Group-related state
const groups = ref([])
const loadingGroups = ref(false)
const showGroupModal = ref(false)
const editingGroup = ref(null)
const savingGroup = ref(false)
const groupForm = ref({
  name: '',
  student_ids: []
})
const groupErrors = ref({})
const studentSearchInGroup = ref('')
const showDeleteGroupModal = ref(false)
const groupToDelete = ref(null)
const deletingGroup = ref(false)
const canCreateGroup = ref(true)

// Group Records Modal state
const showGroupRecordsModal = ref(false)
const selectedGroupForRecords = ref(null)
const savingGroupRecords = ref(false)
const groupRecordForm = ref({
  date: new Date().toISOString().split('T')[0],
  attendance: 'Present',
  reason: '',
  reschedule: '',
  homework: 'Done',
  progress: '',
  notes: '',
  selectedStudentIds: []
})

// Group Syllabus Modal state
const showGroupSyllabusModal = ref(false)
const selectedGroupForSyllabus = ref(null)
const savingGroupSyllabus = ref(false)
const loadingGroupSyllabus = ref(false)
const selectedSyllabusLevel = ref('A1')
const selectedSyllabusTopics = ref([])
const syllabusLevels = ['A1', 'A2', 'B1', 'B2', 'Final Prep']

// Group Attendance Modal state
const showGroupAttendanceModal = ref(false)
const selectedGroupForAttendance = ref(null)
const savingGroupAttendance = ref(false)
const loadingGroupAttendance = ref(false)
const attendanceForm = ref({
  date: new Date().toISOString().split('T')[0],
  progress: '',
  notes: '',
  studentAttendance: {} // { studentId: 'Present' | 'Absent (Notice Given)' | 'Missed (No Notice)' }
})
const attendanceOptions = ['Present', 'Absent (Notice Given)', 'Missed (No Notice)']

// Manage Group Modal (Unified) state
const showManageGroupModal = ref(false)
const selectedManageGroup = ref(null)
const manageGroupTab = ref('attendance')
const savingManageGroup = ref(false)
const loadingManageGroupData = ref(false)
const _initDate = (() => { const d = new Date(); return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}` })()
const manageGroupAttendance = ref({
  date: _initDate,
  notes: '',
  studentStatuses: {},
  studentReasons: {}
})
const manageGroupSyllabusLevel = ref('A1')
const manageGroupSelectedTopics = ref([])
const manageGroupSyllabusForm = ref([])

// Session Timer state — backed by Pinia store so it persists across tab navigation
const sessionTimerRunning = computed(() => timerStore.isTimerRunning)
const sessionTimerSeconds = computed(() => timerStore.sessionTimerSeconds)
const sessionTimerStopped = computed({
  get: () => timerStore.sessionTimerStopped,
  set: (val) => { timerStore.sessionTimerStopped = val }
})
const showStopTimerConfirm = ref(false)
const showTimerConflictDialog = ref(false)
// Sticky widget: shown when timer is running but modal is closed (while on this page)
const timerIsSticky = ref(false)
// Wake Lock sentinel
let wakeLock = null
const showGroupUnsavedConfirm = ref(false)

// Default syllabus topics data - must match StudentRecordsModal.vue
const defaultSyllabusTopics = [
  // A1 Level
  { level: 'A1', topic: 'The French Alphabet' },
  { level: 'A1', topic: 'Accent Marks (é, è, ê, ë, ç, etc.)' },
  { level: 'A1', topic: 'Letter Combinations (Ç, AI, EI, AN, AM, EN, EM, ...)' },
  { level: 'A1', topic: 'Silent Letters (D, E, G, H, S, T, X) and nasal vowels' },
  { level: 'A1', topic: 'Basic Greetings and Farewells' },
  { level: 'A1', topic: 'Introducing Yourself' },
  { level: 'A1', topic: 'Classroom/survival phrases; tu vs vous, pardon, répétez s\'il vous plaît' },
  { level: 'A1', topic: 'Numbers (0-100) & intro to ordinal numbers' },
  { level: 'A1', topic: 'Days of the Week and Months' },
  { level: 'A1', topic: 'Telling the Time' },
  { level: 'A1', topic: 'Telling the Date' },
  { level: 'A1', topic: 'Weather' },
  { level: 'A1', topic: 'Definite and Indefinite Articles (le, la, un, une, etc.)' },
  { level: 'A1', topic: 'Gender & plural of nouns' },
  { level: 'A1', topic: 'Contractions with à/de (au, du, aux, des)' },
  { level: 'A1', topic: 'C\'est / Ce sont' },
  { level: 'A1', topic: 'Il y a' },
  { level: 'A1', topic: 'Subject Pronouns (je, tu, il, elle, etc.)' },
  { level: 'A1', topic: 'Basic Verbs in Present Tense (être, avoir, aller, faire)' },
  { level: 'A1', topic: 'First Group Verbs (-er verbs)' },
  { level: 'A1', topic: 'Spelling-change -er verbs (mangeons, commençons, j\'achète, je préfère, j\'appelle)' },
  { level: 'A1', topic: 'Introduction to Infinitive with 1st group verbs' },
  { level: 'A1', topic: 'Basic Negation (ne...pas)' },
  { level: 'A1', topic: 'Asking Questions (est-ce que, intonation)' },
  { level: 'A1', topic: 'Basic connectors (et, mais, ou, parce que, donc)' },
  { level: 'A1', topic: 'Common Adjectives' },
  { level: 'A1', topic: 'Descriptive Adjectives' },
  { level: 'A1', topic: 'Adjective agreement & position (basics)' },
  { level: 'A1', topic: 'Basic Physical description' },
  { level: 'A1', topic: 'Colors' },
  { level: 'A1', topic: 'Clothing' },
  { level: 'A1', topic: 'Family Members' },
  { level: 'A1', topic: 'Animals' },
  { level: 'A1', topic: 'Countries, cities, nationalities and prepositions' },
  { level: 'A1', topic: 'Parts of the Body' },
  { level: 'A1', topic: 'Furniture and household items' },
  { level: 'A1', topic: 'Food and Drinks' },
  { level: 'A1', topic: 'Prepositions of Place (sur, sous, devant, etc.)' },
  { level: 'A1', topic: 'Talking About Daily Routines' },
  { level: 'A1', topic: 'Reflexive verbs (present) (se lever, s\'appeler...)' },
  { level: 'A1', topic: 'Imperative (tu / nous / vous)' },
  { level: 'A1', topic: 'Second-group verbs (-ir, -issons) present' },
  { level: 'A1', topic: 'Prepositions of time (à + hour; en + month/year)' },
  { level: 'A1', topic: 'Partitive basics (du, de la, de l\', des → pas de)' },
  { level: 'A1', topic: 'Third-group basics (present) (prendre, venir, partir/sortir/dormir)' },
  { level: 'A1', topic: 'Means of transport (le train, la voiture)' },
  { level: 'A1', topic: 'Jouer + Sports' },
  { level: 'A1', topic: 'Jouer + Musical instruments' },
  { level: 'A1', topic: 'Transport prepositions: à / en' },
  { level: 'A1', topic: 'Possessive Adjectives' },
  { level: 'A1', topic: 'Demonstrative adjectives (ce/cet/cette/ces)' },
  { level: 'A1', topic: 'Possession with de (le livre de Marie)' },
  { level: 'A1', topic: 'Forming Negative Sentences' },
  { level: 'A1', topic: 'Interrogative Words' },
  { level: 'A1', topic: 'Forming Interrogative Sentences' },
  { level: 'A1', topic: 'Basic Connectors' },
  { level: 'A1', topic: 'Prices & shopping (combien, ça coûte...)' },
  { level: 'A1', topic: 'Places in town' },
  { level: 'A1', topic: 'Directions in town (à gauche/droite, tout droit)' },
  { level: 'A1', topic: 'Infinitive with 1st, 2nd, and 3rd group verbs' },
  { level: 'A1', topic: '10 Expressions & Idioms' },
  { level: 'A1', topic: 'Practice Exercises A1' },
  { level: 'A1', topic: 'A1 Test' },
  // A2 Level
  { level: 'A2', topic: 'Numbers 100-1,000,000' },
  { level: 'A2', topic: 'Ordinal Numbers (1er, 2e, 3e...)' },
  { level: 'A2', topic: 'Adverbs of Frequency' },
  { level: 'A2', topic: 'Adverbs of Manner' },
  { level: 'A2', topic: 'Comparatives & superlatives (plus/moins/aussi... que; meilleur/mieux; le plus/le moins)' },
  { level: 'A2', topic: 'Partitive Articles' },
  { level: 'A2', topic: 'Quantifiers (beaucoup de, assez de, trop de, peu de, autant de)' },
  { level: 'A2', topic: 'Partitive in negation (du/de la/des → pas de)' },
  { level: 'A2', topic: 'Introduction to le Passé Récent' },
  { level: 'A2', topic: 'Reflexive Verbs (present - quick review)' },
  { level: 'A2', topic: 'Le Passé Composé (1st, 2nd, 3rd group verbs)' },
  { level: 'A2', topic: 'Le Passé Composé: être or avoir' },
  { level: 'A2', topic: 'Reflexive verbs in passé composé (me/te/se/nous/vous + être)' },
  { level: 'A2', topic: 'L\'Imparfait' },
  { level: 'A2', topic: 'Le Passé Composé vs L\'Imparfait' },
  { level: 'A2', topic: 'Depuis / Pendant / Pour / il y a' },
  { level: 'A2', topic: 'COD & Direct Object Pronouns' },
  { level: 'A2', topic: 'COI & Indirect Object Pronouns' },
  { level: 'A2', topic: 'Pronoun order (infinitive & passé composé)' },
  { level: 'A2', topic: 'Relative Pronouns: qui, que & où' },
  { level: 'A2', topic: 'Le Participe (participe présent / rappel participe passé)' },
  { level: 'A2', topic: 'Le Gérondif' },
  { level: 'A2', topic: 'Futur Proche (aller + infinitif)' },
  { level: 'A2', topic: 'Le Futur Simple' },
  { level: 'A2', topic: 'Futur proche vs futur simple (usage)' },
  { level: 'A2', topic: 'Modal verbs + infinitive (devoir / pouvoir / vouloir)' },
  { level: 'A2', topic: 'Infinitive of purpose & sequence (pour / afin de / sans / avant de + infinitif)' },
  { level: 'A2', topic: 'Talking About Future Plans' },
  { level: 'A2', topic: 'L\'Impératif' },
  { level: 'A2', topic: 'Means of Transport (la voiture, le train)' },
  { level: 'A2', topic: 'Transport prepositions à / en' },
  { level: 'A2', topic: 'Asking for and Giving Directions' },
  { level: 'A2', topic: 'Describing Daily Life' },
  { level: 'A2', topic: 'Health and Body Vocabulary' },
  { level: 'A2', topic: 'Jobs & Professions' },
  { level: 'A2', topic: 'Demonstrative Adjectives and Pronouns' },
  { level: 'A2', topic: 'Possessive Pronouns (le mien, le tien, etc.)' },
  { level: 'A2', topic: '10 Expressions & Idioms' },
  { level: 'A2', topic: 'Practice Exercises A2' },
  { level: 'A2', topic: 'A2 Test' },
  // B1 Level
  { level: 'B1', topic: 'Le Passé Récent' },
  { level: 'B1', topic: 'Plus-que-parfait' },
  { level: 'B1', topic: 'Le Passé Composé VS Le Plus-que-parfait' },
  { level: 'B1', topic: 'Conditionnel Présent' },
  { level: 'B1', topic: 'Futur Simple VS Conditionnel Présent' },
  { level: 'B1', topic: 'Si Clauses (type 1 & 2)' },
  { level: 'B1', topic: 'Si Clauses (type 3 - conditionnel passé)' },
  { level: 'B1', topic: 'Relative Pronouns (qui, que, où)' },
  { level: 'B1', topic: 'Relative Pronoun "dont" (intro)' },
  { level: 'B1', topic: 'Relative Pronoun "lequel"' },
  { level: 'B1', topic: 'Infinitive Constructions' },
  { level: 'B1', topic: 'Causative: faire + infinitif' },
  { level: 'B1', topic: 'Passive Voice (simple tenses)' },
  { level: 'B1', topic: 'Passive Voice (compound tenses)' },
  { level: 'B1', topic: 'Pronoun "Y"' },
  { level: 'B1', topic: 'Pronoun "En"' },
  { level: 'B1', topic: 'Indefinite Pronouns' },
  { level: 'B1', topic: 'Compound Prepositions' },
  { level: 'B1', topic: 'Prepositions with Countries, Regions & Cities' },
  { level: 'B1', topic: 'Indirect Speech' },
  { level: 'B1', topic: 'Reported Questions in Indirect Speech' },
  { level: 'B1', topic: 'Impersonal Verbs' },
  { level: 'B1', topic: 'Le Subjonctif Présent' },
  { level: 'B1', topic: 'L\'Indicatif VS Le Subjonctif' },
  { level: 'B1', topic: 'Expressing Cause and Effect' },
  { level: 'B1', topic: 'Cause/Consequence nuance (à cause de, grâce à, en raison de)' },
  { level: 'B1', topic: 'Connector pack (cependant, pourtant, en revanche, puisque, par conséquent, en outre)' },
  { level: 'B1', topic: 'Nominalization' },
  { level: 'B1', topic: 'Using "Tout"' },
  { level: 'B1', topic: 'Advanced Negatives (ne... jamais, ne... plus, etc.)' },
  { level: 'B1', topic: 'Independent and Subordinate Clauses' },
  { level: 'B1', topic: 'Emphatic Pronouns (moi, toi, etc.)' },
  { level: 'B1', topic: 'Pronunciation: liaison & enchaînement' },
  { level: 'B1', topic: 'Les Faux Amis' },
  { level: 'B1', topic: 'Opinion and Debate Phrases' },
  { level: 'B1', topic: 'Formal and Informal Email Writing' },
  { level: 'B1', topic: 'Structuring Long Arguments' },
  { level: 'B1', topic: '10 Expressions & Idioms' },
  { level: 'B1', topic: 'B1 Test' },
  // B2 Level
  { level: 'B2', topic: 'Tense & Mood Review' },
  { level: 'B2', topic: 'Révision des temps (j\'ai fini; j\'étais; je partirai)' },
  { level: 'B2', topic: 'Futur antérieur (quand il sera arrivé...)' },
  { level: 'B2', topic: 'Conditionnel passé' },
  { level: 'B2', topic: 'Phrases conditionnelles 2-3, mixtes (si j\'avais su... je serais venu)' },
  { level: 'B2', topic: 'Conditionnel « journalistique » (il aurait démissionné)' },
  { level: 'B2', topic: 'Subjonctif passé' },
  { level: 'B2', topic: 'Concession / but / conséquence au subjonctif (bien que...; pour que...)' },
  { level: 'B2', topic: 'Infinitif passé' },
  { level: 'B2', topic: 'Reported Speech & Time References' },
  { level: 'B2', topic: 'Discours indirect: concordance (il a dit qu\'il viendrait)' },
  { level: 'B2', topic: 'Repères temporels avancés' },
  { level: 'B2', topic: 'Atténuation / modalisation (il se peut que...; il semblerait que...)' },
  { level: 'B2', topic: 'Pronouns & Complex Constructions' },
  { level: 'B2', topic: 'Pronoms relatifs : ce qui / ce que / ce dont' },
  { level: 'B2', topic: 'Pronoms relatifs: auquel/duquel + démonstratifs (la ville à laquelle...; celui dont...)' },
  { level: 'B2', topic: 'Ordre des pronoms (le lui; lui en; donne-le-moi)' },
  { level: 'B2', topic: 'Y & EN (avancé) (j\'y tiens; je m\'en souviens)' },
  { level: 'B2', topic: 'Verbes avec « s\'en » (il s\'en va; elle s\'en sort)' },
  { level: 'B2', topic: 'Pronoms indéfinis (quelqu\'un; aucun; plusieurs)' },
  { level: 'B2', topic: 'Pronoms démonstratifs indéfinis (ceci; cela; ça)' },
  { level: 'B2', topic: 'Ne explétif (avant qu\'il ne parte)' },
  { level: 'B2', topic: 'Euphonic "ne" in stylistic/formal writing' },
  { level: 'B2', topic: 'Constructions causatives (faire/se faire/se laisser/se voir + inf.)' },
  { level: 'B2', topic: 'Double causative with reflexives (Elle s\'est fait couper les cheveux)' },
  { level: 'B2', topic: 'Passif pronominal' },
  { level: 'B2', topic: 'Syntax & Sentence Variety' },
  { level: 'B2', topic: 'Inversions complexes' },
  { level: 'B2', topic: 'Non seulement... mais aussi (+ inversion) (non seulement a-t-il accepté...)' },
  { level: 'B2', topic: 'Clivage pour insistance (c\'est... qui/que) (c\'est moi qui décide)' },
  { level: 'B2', topic: 'On vs L\'on (on dit...; l\'on constate...)' },
  { level: 'B2', topic: 'Vocabulary & Grammar Refinement' },
  { level: 'B2', topic: 'Prépositions avancées (dès lundi; à partir de...)' },
  { level: 'B2', topic: 'Rection verbale avancée (verbe + à/de + inf.) (se mettre à...; permettre de...)' },
  { level: 'B2', topic: 'Articles : révision (du café; sans sucre)' },
  { level: 'B2', topic: 'C\'est vs Il est (c\'est un problème; il est important de...)' },
  { level: 'B2', topic: 'Adjectifs avancés (une ancienne collègue; bleu clair)' },
  { level: 'B2', topic: 'Pluriels irréguliers (œil/yeux; travail/travaux)' },
  { level: 'B2', topic: 'Préfixes & suffixes (imprévisible; refaire)' },
  { level: 'B2', topic: 'Quantificateurs avancés (la plupart; un grand nombre de; maints)' },
  { level: 'B2', topic: 'Discourse & Style' },
  { level: 'B2', topic: 'Organisateurs & connecteurs avancés (d\'abord... ensuite... toutefois...)' },
  { level: 'B2', topic: 'Connectors of hypothesis (au cas où, à condition que, pourvu que)' },
  { level: 'B2', topic: 'Paraphrase & reformulation (autrement dit...; en d\'autres termes...)' },
  { level: 'B2', topic: 'Résumé & synthèse (idée principale; en résumé...)' },
  { level: 'B2', topic: 'Ponctuation formelle (« Bonjour ! »; espace avant : ; ? !)' },
  { level: 'B2', topic: 'Rédaction structurée (thèse → arguments → conclusion)' },
  { level: 'B2', topic: 'Ironie & métaphore (« C\'est du propre ! »; marathon)' },
  { level: 'B2', topic: 'Lecture authentique : reconnaissance (il arriva; quoiqu\'il fût...)' },
  { level: 'B2', topic: 'Expressions & idiomes (tenir au courant; faire le point; tomber à pic...)' },
  // Final Prep
  { level: 'Final Prep', topic: 'Final Prep' },
  { level: 'Final Prep', topic: 'Révisions finales (quiz + remédiation)' },
  { level: 'Final Prep', topic: 'Practice Exam' },
  { level: 'Final Prep', topic: 'PrepMyFuture' }
]

// Initialize syllabus form with default values
const groupSyllabusForm = ref(
  defaultSyllabusTopics.map(() => ({
    status: '--',
    date: ''
  }))
)

const filteredStudents = computed(() => {
  const filtered = students.value.filter(student => {
    const name = `${student.first_name || ''} ${student.last_name || ''}`.trim() || student.name || ''
    return name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
           (student.email || '').toLowerCase().includes(searchQuery.value.toLowerCase())
  })
  // Sort alphabetically by name
  return filtered.sort((a, b) => {
    const nameA = (a.name || `${a.first_name || ''} ${a.last_name || ''}`).trim().toLowerCase()
    const nameB = (b.name || `${b.first_name || ''} ${b.last_name || ''}`).trim().toLowerCase()
    return nameA.localeCompare(nameB)
  })
})

// Filtered students for group modal
const filteredStudentsForGroup = computed(() => {
  const search = studentSearchInGroup.value.toLowerCase()
  return students.value
    .filter(student => {
      const name = (student.name || '').toLowerCase()
      const email = (student.email || '').toLowerCase()
      return name.includes(search) || email.includes(search)
    })
    .sort((a, b) => {
      const nameA = (a.name || '').toLowerCase()
      const nameB = (b.name || '').toLowerCase()
      return nameA.localeCompare(nameB)
    })
})

// Filtered syllabus items by selected level
const filteredSyllabusItems = computed(() => {
  return defaultSyllabusTopics.filter(item => item.level === selectedSyllabusLevel.value)
})

// Check if all topics in current level are selected
const isAllTopicsSelected = computed(() => {
  const currentLevelIndices = filteredSyllabusItems.value.map(item => getTopicIndex(item))
  return currentLevelIndices.length > 0 && currentLevelIndices.every(index => selectedSyllabusTopics.value.includes(index))
})

// Check if all students in group are marked present
// Attendance count computed properties
const presentCount = computed(() => {
  return Object.values(attendanceForm.value.studentAttendance).filter(status => status === 'Present').length
})
const absentNoticeCount = computed(() => {
  return Object.values(attendanceForm.value.studentAttendance).filter(status => status === 'Absent (Notice Given)').length
})
const missedCount = computed(() => {
  return Object.values(attendanceForm.value.studentAttendance).filter(status => status === 'Missed (No Notice)').length
})

// Check if all students in group are selected for records update
const isAllRecordsStudentsSelected = computed(() => {
  const studentCount = selectedGroupForRecords.value?.students?.length || 0
  return studentCount > 0 && groupRecordForm.value.selectedStudentIds.length === studentCount
})

const loadStudents = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tutor/students')
    
    console.log('Students API Response:', response.data)
    
    if (response.data.success && response.data.data) {
      // Handle paginated response
      let studentsData = []
      if (response.data.data.data && Array.isArray(response.data.data.data)) {
        studentsData = response.data.data.data
      } else if (Array.isArray(response.data.data)) {
        studentsData = response.data.data
      }
      
      console.log('Students Data:', studentsData)
      
      // Process students data
      studentsData = studentsData.map(student => {
        // Set name
        const name = `${student.first_name || ''} ${student.last_name || ''}`.trim() || student.name || 'Unknown'
        
        // Set status (default to Active if student exists)
        const status = 'Active'
        
        return {
          ...student,
          name: name,
          status: status,
          email: student.email || 'N/A'
      }
      })
      
      students.value = studentsData
      console.log('Final students array:', students.value)
    } else {
      console.log('No students data in response')
      students.value = []
    }
  } catch (error) {
    console.error('Failed to load students:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Failed to load students'
    toast.error(errorMessage)
    students.value = [] // Set empty array on error
  } finally {
    loading.value = false
  }
}

const removeStudent = (studentId, studentName) => {
  const student = students.value.find(s => s.id === studentId)
  studentToRemove.value = student || { id: studentId, name: studentName }
  showRemoveModal.value = true
}

const closeRemoveModal = () => {
  showRemoveModal.value = false
  studentToRemove.value = null
}

let searchTimeout = null

const searchStudent = async () => {
  // Clear previous timeout
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }

  if (!studentSearchEmail.value || !studentSearchEmail.value.includes('@')) {
    foundStudent.value = null
    studentSearchError.value = ''
    return
  }

  // Debounce search
  searchTimeout = setTimeout(async () => {
    searchingStudent.value = true
    foundStudent.value = null
    studentSearchError.value = ''
    
    try {
      // Search for student by email using backend search
      const response = await axios.get('/api/admin/students', {
        params: { search: studentSearchEmail.value }
      })

      if (response.data.success && response.data.data) {
        const studentsData = response.data.data.data || response.data.data || []
        const student = Array.isArray(studentsData) 
          ? studentsData.find(s => {
              const email = s.email?.toLowerCase() || ''
              return email === studentSearchEmail.value.toLowerCase() && s.user_type === 'student'
            })
          : null

        if (student) {
          // Check if already assigned
          const isAssigned = students.value.some(s => s.id === student.id)
          if (isAssigned) {
            foundStudent.value = null
            studentSearchError.value = 'This student is already in your list'
            return
          }

          foundStudent.value = {
            id: student.id,
            name: `${student.first_name || ''} ${student.last_name || ''}`.trim() || student.name || 'Unknown',
            email: student.email
          }
          studentSearchError.value = ''
        } else {
          foundStudent.value = null
          studentSearchError.value = 'Student not found with this email'
        }
      }
    } catch (error) {
      console.error('Failed to search student:', error)
      foundStudent.value = null
      studentSearchError.value = 'Failed to search student'
    } finally {
      searchingStudent.value = false
    }
  }, 500) // 500ms debounce
}

const closeAddModal = () => {
  showAddModal.value = false
  studentSearchEmail.value = ''
  foundStudent.value = null
  studentSearchError.value = ''
  searchingStudent.value = false
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
}

const confirmAddStudent = async () => {
  if (!foundStudent.value) return

  addingStudent.value = true
  try {
    // Check if student is already assigned
    const isAlreadyAssigned = students.value.some(s => s.id === foundStudent.value.id)
    if (isAlreadyAssigned) {
      toast.error('This student is already in your list')
      addingStudent.value = false
      return
    }

    // Add student assignment
    const response = await axios.post(`/api/tutor/add-student`, {
      student_id: foundStudent.value.id
    })
    
    if (response.data.success) {
      toast.success(`Student ${foundStudent.value.name} added successfully`)
      closeAddModal()
      // Reload students list
      await loadStudents()
    }
  } catch (error) {
    console.error('Failed to add student:', error)
    const errorMessage = error.response?.data?.message || 'Failed to add student'
    toast.error(errorMessage)
  } finally {
    addingStudent.value = false
  }
}

const confirmRemove = async () => {
  if (!studentToRemove.value) return

  const studentId = studentToRemove.value.id
  const studentName = studentToRemove.value.name

  removingStudent.value = studentId
  try {
    const response = await axios.delete(`/api/tutor/remove-student/${studentId}`)
    
    if (response.data.success) {
      toast.success(`Student ${studentName} removed successfully`)
      // Remove from local list
      students.value = students.value.filter(s => s.id !== studentId)
      // Reload groups to update group student counts
      await loadGroups()
      closeRemoveModal()
    }
  } catch (error) {
    console.error('Failed to remove student:', error)
    const errorMessage = error.response?.data?.message || 'Failed to remove student'
    toast.error(errorMessage)
  } finally {
    removingStudent.value = null
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

// Get the group name for a student
const getStudentGroup = (studentId) => {
  for (const group of groups.value) {
    if (group.students?.some(s => s.id === studentId)) {
      return group.name
    }
  }
  return null
}

const openRecords = (student) => {
  selectedStudentId.value = student.id
  selectedStudentName.value = student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || 'Student'
  showRecordsModal.value = true
}

const closeRecordsModal = () => {
  showRecordsModal.value = false
  selectedStudentId.value = null
  selectedStudentName.value = ''
}

// Group-related methods
const loadGroups = async () => {
  loadingGroups.value = true
  try {
    const response = await axios.get('/api/tutor/groups')
    if (response.data.success) {
      groups.value = response.data.data || []
    }
  } catch (error) {
    console.error('Failed to load groups:', error)
    toast.error('Failed to load groups')
  } finally {
    loadingGroups.value = false
  }
}

const loadGroupCount = async () => {
  try {
    const response = await axios.get('/api/tutor/groups/count')
    if (response.data.success) {
      canCreateGroup.value = response.data.data.can_create
    }
  } catch (error) {
    console.error('Failed to load group count:', error)
  }
}

const openCreateGroupModal = () => {
  if (!canCreateGroup.value) {
    toast.error('You can create a maximum of 5 groups.')
    return
  }
  editingGroup.value = null
  groupForm.value = {
    name: '',
    student_ids: []
  }
  groupErrors.value = {}
  studentSearchInGroup.value = ''
  showGroupModal.value = true
}

const openEditGroupModal = (group) => {
  editingGroup.value = group
  groupForm.value = {
    name: group.name,
    student_ids: group.students.map(s => s.id)
  }
  groupErrors.value = {}
  studentSearchInGroup.value = ''
  showGroupModal.value = true
}

const closeGroupModal = () => {
  showGroupModal.value = false
  editingGroup.value = null
  groupForm.value = {
    name: '',
    student_ids: []
  }
  groupErrors.value = {}
  studentSearchInGroup.value = ''
}

const toggleStudentSelection = (studentId) => {
  const index = groupForm.value.student_ids.indexOf(studentId)
  if (index === -1) {
    groupForm.value.student_ids.push(studentId)
  } else {
    groupForm.value.student_ids.splice(index, 1)
  }
}

const validateGroupForm = () => {
  groupErrors.value = {}
  let isValid = true

  if (!groupForm.value.name || !groupForm.value.name.trim()) {
    groupErrors.value.name = 'Group name is required'
    isValid = false
  }

  if (groupForm.value.student_ids.length === 0) {
    groupErrors.value.students = 'Please select at least 1 student'
    isValid = false
  }

  return isValid
}

const saveGroup = async () => {
  if (!validateGroupForm()) return

  savingGroup.value = true
  try {
    if (editingGroup.value) {
      // Update existing group
      const response = await axios.put(`/api/tutor/groups/${editingGroup.value.id}`, {
        name: groupForm.value.name.trim(),
        student_ids: groupForm.value.student_ids
      })
      if (response.data.success) {
        toast.success('Group updated successfully')
        // Update the group in the list
        const index = groups.value.findIndex(g => g.id === editingGroup.value.id)
        if (index !== -1) {
          groups.value[index] = response.data.data
        }
        closeGroupModal()
      }
    } else {
      // Create new group
      const response = await axios.post('/api/tutor/groups', {
        name: groupForm.value.name.trim(),
        student_ids: groupForm.value.student_ids
      })
      if (response.data.success) {
        toast.success('Group created successfully')
        groups.value.unshift(response.data.data)
        canCreateGroup.value = groups.value.length < 5
        closeGroupModal()
      }
    }
  } catch (error) {
    console.error('Failed to save group:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save group'
    toast.error(errorMessage)
  } finally {
    savingGroup.value = false
  }
}

const confirmDeleteGroup = (group) => {
  groupToDelete.value = group
  showDeleteGroupModal.value = true
}

const closeDeleteGroupModal = () => {
  showDeleteGroupModal.value = false
  groupToDelete.value = null
}

const deleteGroup = async () => {
  if (!groupToDelete.value) return

  deletingGroup.value = true
  try {
    const response = await axios.delete(`/api/tutor/groups/${groupToDelete.value.id}`)
    if (response.data.success) {
      toast.success(response.data.message || 'Group deleted successfully')
      groups.value = groups.value.filter(g => g.id !== groupToDelete.value.id)
      canCreateGroup.value = groups.value.length < 5
      closeDeleteGroupModal()
    }
  } catch (error) {
    console.error('Failed to delete group:', error)
    const errorMessage = error.response?.data?.message || 'Failed to delete group'
    toast.error(errorMessage)
  } finally {
    deletingGroup.value = false
  }
}

// Group Records Modal methods
const openGroupRecordsModal = (group) => {
  selectedGroupForRecords.value = group
  groupRecordForm.value = {
    date: new Date().toISOString().split('T')[0],
    attendance: 'Present',
    reason: '',
    reschedule: '',
    homework: 'Done',
    progress: '',
    notes: '',
    selectedStudentIds: group.students?.map(s => s.id) || [] // Select all by default
  }
  showGroupRecordsModal.value = true
}

const closeGroupRecordsModal = () => {
  showGroupRecordsModal.value = false
  selectedGroupForRecords.value = null
}

const toggleRecordsStudentSelection = (studentId) => {
  const index = groupRecordForm.value.selectedStudentIds.indexOf(studentId)
  if (index === -1) {
    groupRecordForm.value.selectedStudentIds.push(studentId)
  } else {
    groupRecordForm.value.selectedStudentIds.splice(index, 1)
  }
}

const toggleAllRecordsStudents = () => {
  if (isAllRecordsStudentsSelected.value) {
    groupRecordForm.value.selectedStudentIds = []
  } else {
    groupRecordForm.value.selectedStudentIds = selectedGroupForRecords.value?.students?.map(s => s.id) || []
  }
}

const saveGroupRecords = async () => {
  if (!selectedGroupForRecords.value || !groupRecordForm.value.date) {
    toast.error('Please select a date')
    return
  }

  if (groupRecordForm.value.selectedStudentIds.length === 0) {
    toast.error('Please select at least one student')
    return
  }

  savingGroupRecords.value = true
  try {
    const response = await axios.post(`/api/tutor/groups/${selectedGroupForRecords.value.id}/bulk-records`, {
      record: {
        date: groupRecordForm.value.date,
        attendance: groupRecordForm.value.attendance,
        reason: groupRecordForm.value.reason || null,
        reschedule: groupRecordForm.value.reschedule || null,
        homework: groupRecordForm.value.homework,
        progress: groupRecordForm.value.progress || null,
        notes: groupRecordForm.value.notes || null
      },
      selected_student_ids: groupRecordForm.value.selectedStudentIds
    })

    if (response.data.success) {
      toast.success(`Records added for ${response.data.data.students_updated} students`)
      closeGroupRecordsModal()
    }
  } catch (error) {
    console.error('Failed to save group records:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save records'
    toast.error(errorMessage)
  } finally {
    savingGroupRecords.value = false
  }
}

// Group Syllabus Modal methods
const getTopicIndex = (item) => {
  return defaultSyllabusTopics.findIndex(t => t.level === item.level && t.topic === item.topic)
}

const openGroupSyllabusModal = async (group) => {
  selectedGroupForSyllabus.value = group
  selectedSyllabusLevel.value = 'A1'
  selectedSyllabusTopics.value = []
  // Reset form to default values
  groupSyllabusForm.value = defaultSyllabusTopics.map(() => ({
    status: '--',
    date: ''
  }))
  showGroupSyllabusModal.value = true

  // Load existing syllabus data for the group
  loadingGroupSyllabus.value = true
  try {
    const response = await axios.get(`/api/tutor/groups/${group.id}/syllabus`)
    if (response.data.success && response.data.data) {
      const existingSyllabus = response.data.data

      // Pre-populate checkboxes and form with existing completed topics
      existingSyllabus.forEach(item => {
        const index = defaultSyllabusTopics.findIndex(
          t => t.level === item.level && t.topic === item.topic
        )
        if (index !== -1) {
          // Add to selected topics
          if (!selectedSyllabusTopics.value.includes(index)) {
            selectedSyllabusTopics.value.push(index)
          }
          // Update form with status and date
          groupSyllabusForm.value[index] = {
            status: item.status || 'Completed',
            date: item.date || ''
          }
        }
      })
    }
  } catch (error) {
    console.error('Failed to load group syllabus:', error)
  } finally {
    loadingGroupSyllabus.value = false
  }
}

const closeGroupSyllabusModal = () => {
  showGroupSyllabusModal.value = false
  selectedGroupForSyllabus.value = null
  selectedSyllabusTopics.value = []
}

const toggleTopicSelection = (index) => {
  const idx = selectedSyllabusTopics.value.indexOf(index)
  if (idx === -1) {
    selectedSyllabusTopics.value.push(index)
  } else {
    selectedSyllabusTopics.value.splice(idx, 1)
  }
}

const toggleAllTopics = () => {
  const currentLevelIndices = filteredSyllabusItems.value.map(item => getTopicIndex(item))

  if (isAllTopicsSelected.value) {
    // Deselect all topics in current level
    selectedSyllabusTopics.value = selectedSyllabusTopics.value.filter(
      index => !currentLevelIndices.includes(index)
    )
  } else {
    // Select all topics in current level
    currentLevelIndices.forEach(index => {
      if (!selectedSyllabusTopics.value.includes(index)) {
        selectedSyllabusTopics.value.push(index)
      }
    })
  }
}

const saveGroupSyllabus = async () => {
  if (!selectedGroupForSyllabus.value) {
    toast.error('No group selected')
    return
  }

  if (selectedSyllabusTopics.value.length === 0) {
    toast.error('Please select at least one topic to update')
    return
  }

  savingGroupSyllabus.value = true
  try {
    // Build syllabus data for selected topics only
    const syllabusUpdates = selectedSyllabusTopics.value.map(index => ({
      level: defaultSyllabusTopics[index].level,
      topic: defaultSyllabusTopics[index].topic,
      status: groupSyllabusForm.value[index].status,
      date: groupSyllabusForm.value[index].date || null
    }))

    const response = await axios.post(`/api/tutor/groups/${selectedGroupForSyllabus.value.id}/bulk-syllabus`, {
      syllabus: syllabusUpdates
    })

    if (response.data.success) {
      toast.success(`Syllabus updated for ${response.data.data.students_updated} students`)
      closeGroupSyllabusModal()
    }
  } catch (error) {
    console.error('Failed to save group syllabus:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save syllabus'
    toast.error(errorMessage)
  } finally {
    savingGroupSyllabus.value = false
  }
}

// Group Attendance Modal methods
const openGroupAttendanceModal = async (group) => {
  selectedGroupForAttendance.value = group
  const today = new Date().toISOString().split('T')[0]

  // Initialize attendance with default "Present" for all students
  const initialAttendance = {}
  group.students?.forEach(student => {
    initialAttendance[student.id] = 'Present'
  })

  attendanceForm.value = {
    date: today,
    progress: '',
    studentAttendance: initialAttendance
  }
  showGroupAttendanceModal.value = true

  // Load existing attendance for today
  await loadAttendanceForDate(group.id, today)
}

const loadAttendanceForDate = async (groupId, date) => {
  loadingGroupAttendance.value = true
  try {
    const response = await axios.get(`/api/tutor/groups/${groupId}/attendance?date=${date}`)
    if (response.data.success && response.data.data) {
      // Load individual attendance statuses
      const attendanceData = response.data.data.student_attendance || {}
      if (Object.keys(attendanceData).length > 0) {
        // Merge loaded data with existing (for students without data)
        Object.keys(attendanceData).forEach(studentId => {
          attendanceForm.value.studentAttendance[studentId] = attendanceData[studentId]
        })
      }
      if (response.data.data.progress) {
        attendanceForm.value.progress = response.data.data.progress
      }
      if (response.data.data.notes) {
        attendanceForm.value.notes = response.data.data.notes
      }
    }
  } catch (error) {
    console.error('Failed to load attendance:', error)
  } finally {
    loadingGroupAttendance.value = false
  }
}

const closeGroupAttendanceModal = () => {
  showGroupAttendanceModal.value = false
  selectedGroupForAttendance.value = null
}

const onAttendanceDateChange = () => {
  if (selectedGroupForAttendance.value && attendanceForm.value.date) {
    // Reset attendance to default "Present" for all students
    const initialAttendance = {}
    selectedGroupForAttendance.value.students?.forEach(student => {
      initialAttendance[student.id] = 'Present'
    })
    attendanceForm.value.studentAttendance = initialAttendance
    attendanceForm.value.progress = ''
    attendanceForm.value.notes = ''
    loadAttendanceForDate(selectedGroupForAttendance.value.id, attendanceForm.value.date)
  }
}

const markAllAttendance = (status) => {
  selectedGroupForAttendance.value?.students?.forEach(student => {
    attendanceForm.value.studentAttendance[student.id] = status
  })
}

const getStudentInitials = (name) => {
  if (!name) return '?'
  const parts = name.split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

const saveGroupAttendance = async () => {
  if (!selectedGroupForAttendance.value || !attendanceForm.value.date) {
    toast.error('Please select a date')
    return
  }

  savingGroupAttendance.value = true
  try {
    const response = await axios.post(`/api/tutor/groups/${selectedGroupForAttendance.value.id}/bulk-attendance`, {
      date: attendanceForm.value.date,
      progress: attendanceForm.value.progress || null,
      notes: attendanceForm.value.notes || null,
      student_attendance: attendanceForm.value.studentAttendance
    })

    if (response.data.success) {
      const { present_count, absent_notice_count, missed_count } = response.data.data
      toast.success(`Attendance saved: ${present_count} present, ${absent_notice_count} absent (notice), ${missed_count} missed`)
      closeGroupAttendanceModal()
    }
  } catch (error) {
    console.error('Failed to save attendance:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save attendance'
    toast.error(errorMessage)
  } finally {
    savingGroupAttendance.value = false
  }
}

// ===== MANAGE GROUP MODAL METHODS =====

const openManageGroupModal = async (group) => {
  selectedManageGroup.value = group
  manageGroupTab.value = 'attendance'
  
  // Initialize attendance form
  const initialStatuses = {}
  const initialReasons = {}
  group.students?.forEach(student => {
    initialStatuses[student.id] = '--'
    initialReasons[student.id] = ''
  })
  const _d = new Date()
  const _today = `${_d.getFullYear()}-${String(_d.getMonth()+1).padStart(2,'0')}-${String(_d.getDate()).padStart(2,'0')}`
  manageGroupAttendance.value = {
    date: _today,
    notes: '',
    studentStatuses: initialStatuses,
    studentReasons: initialReasons
  }
  
  // Initialize syllabus form with defaults first
  manageGroupSyllabusLevel.value = 'A1'
  manageGroupSelectedTopics.value = []
  manageGroupSyllabusForm.value = defaultSyllabusTopics.map(() => ({
    status: '--',
    date: ''
  }))
  
  // Only reset the timer if it is NOT already running for this group.
  // Reopening the modal while a session is active should show the live timer, not reset it.
  if (timerStore.isTimerRunning && timerStore.timerOwner === 'group') {
    timerIsSticky.value = false // restore the timer section inside the modal
  } else {
    timerStore.resetTimer()
    timerStore.activeGroupName = group.name
    timerIsSticky.value = false
  }
  
  showManageGroupModal.value = true
  
  // Load existing data for today
  await loadManageGroupAttendanceData(group.id, manageGroupAttendance.value.date)
  
  // Load existing syllabus data for the group
  await loadManageGroupSyllabusData(group.id)
}

// Load existing syllabus data for the group
const loadManageGroupSyllabusData = async (groupId) => {
  try {
    const response = await axios.get(`/api/tutor/groups/${groupId}/syllabus`)
    if (response.data.success && response.data.data) {
      const syllabusData = response.data.data
      
      // Update form with existing data
      syllabusData.forEach(item => {
        // Find the matching topic in defaultSyllabusTopics
        const topicIndex = defaultSyllabusTopics.findIndex(
          t => t.level === item.level && t.topic === item.topic
        )
        
        if (topicIndex !== -1) {
          manageGroupSyllabusForm.value[topicIndex] = {
            status: item.status || '--',
            date: item.date || ''
          }
        }
      })
    }
  } catch (error) {
    console.error('Failed to load syllabus data:', error)
  }
}

const closeManageGroupModal = () => {
  if (sessionTimerRunning.value) {
    // Timer is running — minimise to sticky widget instead of blocking
    timerIsSticky.value = true
    showManageGroupModal.value = false
    return
  }

  // Timer was stopped — always prompt before discarding the session record
  if (sessionTimerStopped.value) {
    showGroupUnsavedConfirm.value = true
    return
  }

  // Normal close
  timerIsSticky.value = false
  showManageGroupModal.value = false
  selectedManageGroup.value = null
  timerStore.resetTimer()
  releaseWakeLock()
}

const confirmGroupCloseWithoutSaving = () => {
  showGroupUnsavedConfirm.value = false
  
  // Force clean up and close
  timerIsSticky.value = false
  showManageGroupModal.value = false
  selectedManageGroup.value = null
  timerStore.resetTimer()
  releaseWakeLock()
}

const confirmGroupSaveAndClose = async () => {
  showGroupUnsavedConfirm.value = false
  await saveManageGroupAttendance()
}

// Restore selectedManageGroup from loaded groups after navigation (uses timerStore.timerGroupId)
const restoreGroupForTimer = async () => {
  if (selectedManageGroup.value) return // already set
  if (!timerStore.timerGroupId) return
  if (groups.value.length === 0) await loadGroups()
  const group = groups.value.find(g => g.id === timerStore.timerGroupId)
  if (!group) return
  selectedManageGroup.value = group
  const d = new Date()
  const today = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`
  const initialStatuses = {}
  const initialReasons = {}
  group.students?.forEach(s => { initialStatuses[s.id] = '--'; initialReasons[s.id] = '' })
  manageGroupAttendance.value = { date: today, notes: '', studentStatuses: initialStatuses, studentReasons: initialReasons }
}

const reopenModalFromSticky = async () => {
  await restoreGroupForTimer()
  timerIsSticky.value = false
  showManageGroupModal.value = true
}

// ─── Wake Lock helpers ────────────────────────────────────────────────────────
const acquireWakeLock = async () => {
  if ('wakeLock' in navigator) {
    try {
      wakeLock = await navigator.wakeLock.request('screen')
      // If the page becomes visible again after being hidden, re-acquire
      document.addEventListener('visibilitychange', reacquireWakeLock)
    } catch (err) {
      console.warn('Wake Lock could not be acquired:', err)
    }
  }
}

const reacquireWakeLock = async () => {
  if (!document.hidden && sessionTimerRunning.value && !wakeLock) {
    try {
      wakeLock = await navigator.wakeLock.request('screen')
    } catch (err) {
      console.warn('Wake Lock re-acquire failed:', err)
    }
  }
}

const releaseWakeLock = () => {
  document.removeEventListener('visibilitychange', reacquireWakeLock)
  if (wakeLock) {
    wakeLock.release().catch(() => {})
    wakeLock = null
  }
}

// ─── Session Timer methods ────────────────────────────────────────────────────
// Timer state lives in the Pinia store so it survives page navigation.

const toggleSessionTimer = () => {
  // If timer is stopped (finalized), don't allow restart
  if (timerStore.sessionTimerStopped) {
    return
  }

  if (timerStore.isTimerRunning) {
    // Conflict: a student record timer is running — block and inform the user
    if (timerStore.timerOwner === 'student-record') {
      showTimerConflictDialog.value = true
      return
    }
    // Group session timer is running — show stop confirmation
    showStopTimerConfirm.value = true
  } else {
    // Start timer via store (accumulates any previously elapsed seconds)
    timerStore.startTimer(timerStore.sessionTimerSeconds)
    // Save group ID so it can be restored after page navigation
    timerStore.timerGroupId = selectedManageGroup.value?.id || null
    // Ask browser to keep the screen awake
    acquireWakeLock()
  }
}

const confirmStopTimer = async () => {
  timerStore.stopTimer()
  showStopTimerConfirm.value = false
  timerIsSticky.value = false // Dismiss sticky widget once stopped
  releaseWakeLock()

  // If modal was minimized (or group lost after navigation), reopen it so tutor can save attendance
  if (!showManageGroupModal.value) {
    await restoreGroupForTimer()
    if (selectedManageGroup.value) {
      showManageGroupModal.value = true
    }
  }
}

const cancelStopTimer = () => {
  showStopTimerConfirm.value = false
}

const formatSessionTimer = computed(() => timerStore.formattedTimer)

onUnmounted(() => {
  // Only release wake lock on unmount — timer interval lives in the store and
  // keeps running while the user navigates to other tabs.
  releaseWakeLock()
  // Hide the page-level sticky widget; the layout-level one takes over.
  timerIsSticky.value = false
})

const getFormattedTimer = () => {
  const s = timerStore.sessionTimerSeconds
  const hours = Math.floor(s / 3600)
  const minutes = Math.floor((s % 3600) / 60)
  const seconds = s % 60
  const parts = []
  if (hours > 0) parts.push(`${hours}h`)
  if (minutes > 0) parts.push(`${minutes}m`)
  if (seconds > 0 || parts.length === 0) parts.push(`${seconds}s`)
  return parts.join(' ')
}

// Manage Group Attendance methods
const loadManageGroupAttendanceData = async (groupId, date) => {
  loadingManageGroupData.value = true
  try {
    const response = await axios.get(`/api/tutor/groups/${groupId}/attendance?date=${date}`)
    if (response.data.success && response.data.data) {
      const attendanceData = response.data.data.student_attendance || {}
      const reasonData = response.data.data.student_reasons || {}
      if (Object.keys(attendanceData).length > 0) {
        Object.keys(attendanceData).forEach(studentId => {
          manageGroupAttendance.value.studentStatuses[studentId] = attendanceData[studentId]
          manageGroupAttendance.value.studentReasons[studentId] = reasonData[studentId] || ''
        })
      }
      if (response.data.data.notes) {
        manageGroupAttendance.value.notes = response.data.data.notes
      }
    }
  } catch (error) {
    console.error('Failed to load attendance:', error)
  } finally {
    loadingManageGroupData.value = false
  }
}

const onManageGroupDateChange = () => {
  if (selectedManageGroup.value && manageGroupAttendance.value.date) {
    // Reset statuses to --
    const initialStatuses = {}
    const initialReasons = {}
    selectedManageGroup.value.students?.forEach(student => {
      initialStatuses[student.id] = '--'
      initialReasons[student.id] = ''
    })
    manageGroupAttendance.value.studentStatuses = initialStatuses
    manageGroupAttendance.value.studentReasons = initialReasons
    manageGroupAttendance.value.notes = ''
    loadManageGroupAttendanceData(selectedManageGroup.value.id, manageGroupAttendance.value.date)
  }
}

const markAllManageAttendance = (status) => {
  selectedManageGroup.value?.students?.forEach(student => {
    manageGroupAttendance.value.studentStatuses[student.id] = status
  })
}

// Computed properties for Manage Group modal
const manageGroupPresentCount = computed(() => {
  return Object.values(manageGroupAttendance.value.studentStatuses).filter(s => s === 'Present').length
})

const manageGroupAbsentNoticeCount = computed(() => {
  return Object.values(manageGroupAttendance.value.studentStatuses).filter(s => s === 'Absent (Notice Given)').length
})

const manageGroupMissedCount = computed(() => {
  return Object.values(manageGroupAttendance.value.studentStatuses).filter(s => s === 'Missed (No Notice)').length
})

// Manage Group Syllabus methods
const filteredManageGroupSyllabusTopics = computed(() => {
  return defaultSyllabusTopics.filter(item => item.level === manageGroupSyllabusLevel.value)
})

const getManageGroupTopicIndex = (topic) => {
  return defaultSyllabusTopics.findIndex(t => t.level === topic.level && t.topic === topic.topic)
}

const toggleManageGroupTopic = (index) => {
  const idx = manageGroupSelectedTopics.value.indexOf(index)
  if (idx === -1) {
    manageGroupSelectedTopics.value.push(index)
  } else {
    manageGroupSelectedTopics.value.splice(idx, 1)
  }
}

const isAllManageGroupTopicsSelected = computed(() => {
  const currentLevelIndices = filteredManageGroupSyllabusTopics.value.map(item => getManageGroupTopicIndex(item))
  return currentLevelIndices.length > 0 && currentLevelIndices.every(index => manageGroupSelectedTopics.value.includes(index))
})

const toggleAllManageGroupTopics = () => {
  const currentLevelIndices = filteredManageGroupSyllabusTopics.value.map(item => getManageGroupTopicIndex(item))
  
  if (isAllManageGroupTopicsSelected.value) {
    manageGroupSelectedTopics.value = manageGroupSelectedTopics.value.filter(index => !currentLevelIndices.includes(index))
  } else {
    currentLevelIndices.forEach(index => {
      if (!manageGroupSelectedTopics.value.includes(index)) {
        manageGroupSelectedTopics.value.push(index)
      }
    })
  }
}

// Save Manage Group Attendance
const saveManageGroupAttendance = async () => {
  if (sessionTimerRunning.value) {
    toast.error('Please stop the session timer first — click “Stop” to finalize the time, then save.')
    return
  }

  if (!selectedManageGroup.value) {
    toast.error('No group selected. Please reopen the group and try again.')
    return
  }
  if (!manageGroupAttendance.value.date) {
    toast.error('Please select a date')
    return
  }

  savingManageGroup.value = true
  try {
    const timerValue = sessionTimerSeconds.value > 0 ? getFormattedTimer() : ''
    
    // Validation: Ensure all students have status selected
    const studentIds = selectedManageGroup.value.students?.map(s => s.id) || []
    
    for (const sid of studentIds) {
      const status = manageGroupAttendance.value.studentStatuses[sid]
      if (!status || status === '--') {
        toast.error(`Please select attendance for all students`)
        savingManageGroup.value = false
        return
      }
    }

    const response = await axios.post(`/api/tutor/groups/${selectedManageGroup.value.id}/bulk-attendance`, {
      date: manageGroupAttendance.value.date,
      timer: timerValue,
      notes: manageGroupAttendance.value.notes || null,
      student_attendance: manageGroupAttendance.value.studentStatuses,
      student_reasons: manageGroupAttendance.value.studentReasons
    })

    if (response.data.success) {
      const { present_count, absent_notice_count, missed_count } = response.data.data
      toast.success(`Attendance saved: ${present_count} present, ${absent_notice_count} absent (notice), ${missed_count} missed`)
      timerStore.resetTimer() // clear stopped state so closeManageGroupModal won't re-trigger the dialog
      closeManageGroupModal()
    }
  } catch (error) {
    console.error('Failed to save attendance:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save attendance'
    toast.error(errorMessage)
  } finally {
    savingManageGroup.value = false
  }
}

// Save Manage Group Syllabus
const saveManageGroupSyllabus = async () => {
  if (!selectedManageGroup.value) {
    toast.error('No group selected')
    return
  }

  if (manageGroupSelectedTopics.value.length === 0) {
    toast.error('Please select at least one topic to update')
    return
  }

  savingManageGroup.value = true
  try {
    const syllabusUpdates = manageGroupSelectedTopics.value.map(index => ({
      level: defaultSyllabusTopics[index].level,
      topic: defaultSyllabusTopics[index].topic,
      status: manageGroupSyllabusForm.value[index]?.status || 'Completed',
      date: manageGroupSyllabusForm.value[index]?.date || null
    }))

    const response = await axios.post(`/api/tutor/groups/${selectedManageGroup.value.id}/bulk-syllabus`, {
      syllabus: syllabusUpdates
    })

    if (response.data.success) {
      toast.success(`Syllabus updated for ${response.data.data.students_updated} students`)
      closeManageGroupModal()
    }
  } catch (error) {
    console.error('Failed to save syllabus:', error)
    const errorMessage = error.response?.data?.message || 'Failed to save syllabus'
    toast.error(errorMessage)
  } finally {
    savingManageGroup.value = false
  }
}

// Reopen the student records modal when "Go to Session" is clicked while already
// on this page (reactive case — component is already mounted).
watch(() => timerStore.pendingOpenStudentModal, (pending) => {
  if (pending && timerStore.timerOwner === 'student-record' && timerStore.timerStudentId) {
    selectedStudentId.value = timerStore.timerStudentId
    selectedStudentName.value = timerStore.timerStudentName || ''
    showRecordsModal.value = true
    timerStore.pendingOpenStudentModal = false
  }
})

onMounted(() => {
  loadStudents()
  loadGroups()
  loadGroupCount()
  fetchExamPrepAccessSummary()

  // If a group session timer was running before the user navigated away and back,
  // restore the sticky widget so it shows on this page too.
  if (timerStore.isTimerRunning && timerStore.timerOwner === 'group') {
    timerIsSticky.value = true
  }

  // "Go to Session" navigation case: flag was set before this component mounted,
  // so the reactive watcher above didn't catch it. Handle it here after mount so
  // StudentRecordsModal is already initialised with show=false and will correctly
  // transition to show=true, triggering its loadRecords() watcher.
  if (timerStore.pendingOpenStudentModal && timerStore.timerOwner === 'student-record' && timerStore.timerStudentId) {
    selectedStudentId.value = timerStore.timerStudentId
    selectedStudentName.value = timerStore.timerStudentName || ''
    showRecordsModal.value = true
    timerStore.pendingOpenStudentModal = false
  }
})
</script>

<style scoped>
/* Additional styling if needed */
</style>
