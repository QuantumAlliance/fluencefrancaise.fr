<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Fixed Public Header -->
    <header class="fixed w-full top-0 z-50 bg-white shadow-md">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <div class="flex items-center gap-4">
            <router-link :to="backRootPath" class="md:hidden text-gray-600 hover:text-[#0055A4]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
            </router-link>
            <router-link to="/" class="flex items-center">
              <img
                v-if="settingsStore.siteLogo"
                :src="logoUrl"
                :alt="settingsStore.siteName"
                class="h-10 w-auto"
              />
              <span v-else class="text-2xl font-semibold" style="color: rgb(0, 85, 164)">
                {{ settingsStore.siteName }}
              </span>
            </router-link>
          </div>

          <nav class="hidden md:flex gap-8 items-center">
            <router-link to="/" class="text-gray-700 hover:text-[#0055A4] transition">Home</router-link>
            <template v-if="auth.user?.user_type === 'student' || !auth.user">
              <router-link to="/student/courses" class="text-gray-700 hover:text-[#0055A4] transition">My Courses</router-link>
              <router-link to="/student/dashboard" class="text-gray-700 hover:text-[#0055A4] transition">Dashboard</router-link>
            </template>
            <template v-else-if="auth.user?.user_type === 'tutor'">
              <router-link to="/tutor/courses" class="text-gray-700 hover:text-[#0055A4] transition">Courses</router-link>
              <router-link to="/tutor/dashboard" class="text-gray-700 hover:text-[#0055A4] transition">Dashboard</router-link>
            </template>
            <template v-else-if="auth.user?.user_type === 'admin' || auth.user?.user_type === 'superadmin'">
              <router-link to="/admin/courses" class="text-gray-700 hover:text-[#0055A4] transition">Courses</router-link>
              <router-link to="/admin/dashboard" class="text-gray-700 hover:text-[#0055A4] transition">Dashboard</router-link>
            </template>
          </nav>

          <div class="hidden md:flex gap-4 items-center">
            <router-link
              :to="accountPath"
              class="text-gray-700 hover:text-[#0055A4] transition"
            >
              Account
            </router-link>
            <button
              @click="logout"
              :disabled="isLoggingOut"
              class="px-5 py-2 text-white rounded-lg font-medium transition bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <Loader v-if="isLoggingOut" class="w-4 h-4 animate-spin" />
              {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="pt-20">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
    </div>

    <!-- Course Content -->
    <div v-else-if="course">
      <!-- Header Banner -->
      <div class="relative h-44 md:h-52 overflow-hidden bg-gradient-to-br from-[#0055A4] via-[#003d7a] to-[#002654]">
        <img
          v-if="course.course_banner"
          :src="getBannerImageUrl(course.course_banner)"
          :alt="course.course_title"
          class="absolute inset-0 w-full h-full object-cover"
        />
        <!-- decorative pattern overlay -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 30%, white 0, transparent 40%), radial-gradient(circle at 80% 70%, white 0, transparent 35%);"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>

        <div class="absolute top-5 left-5 z-10">
          <button
            @click="goBack"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white/95 hover:bg-white text-gray-800 rounded-full font-medium text-sm shadow-md transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
          </button>
        </div>

        <div class="absolute inset-x-0 bottom-0 p-5 md:p-8 max-w-6xl mx-auto">
          <p v-if="course.course_category" class="inline-block text-xs font-semibold tracking-wider uppercase text-white/90 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full mb-2">
            {{ course.course_category }}
          </p>
          <h1 class="text-2xl md:text-4xl font-bold text-white drop-shadow-sm">{{ course.course_title }}</h1>
          <p v-if="course.course_subtitle" class="text-sm md:text-base text-white/90 mt-1 line-clamp-1">{{ course.course_subtitle }}</p>
        </div>
      </div>

      <div class="flex max-w-7xl mx-auto">
        <!-- Main Content Area (Left side) -->
        <div class="flex-1 p-8">
          <!-- Course Description Box -->
          <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ course.course_title }}</h2>
            <p class="text-gray-600 mb-4">{{ course.course_subtitle }}</p>
            <p class="text-gray-700 leading-relaxed">{{ course.course_description }}</p>

            <!-- Course Meta Info -->
            <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
              <div>
                <p class="text-gray-500 text-sm font-semibold">Category</p>
                <p class="text-gray-800 font-medium">{{ course.course_category }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Language</p>
                <p class="text-gray-800 font-medium">{{ course.course_language }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Level</p>
                <p class="text-gray-800 font-medium">{{ course.course_level }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Total Readings</p>
                <p class="text-gray-800 font-medium">{{ course.course_total_texts }}</p>
              </div>
            </div>
          </div>

          <!-- Grouped Level Sections -->
          <div class="space-y-6">
            <!-- Group by Difficulty/Level -->
            <div
              v-for="(group, groupKey) in groupedSections"
              :key="groupKey"
              class="bg-white rounded-lg shadow overflow-hidden"
            >
              <!-- Group Header -->
              <button
                @click="toggleGroup(groupKey)"
                class="w-full px-6 py-4 bg-gradient-to-r from-[#0055A4] to-[#003d7a] hover:from-[#003d7a] hover:to-[#002654] flex justify-between items-center transition-colors"
              >
                <div class="flex items-center gap-3">
                  <h2 class="text-xl font-bold text-white">
                    {{ groupKey || 'Other Sections' }}
                  </h2>
                  <span class="text-sm text-white/90 bg-white bg-opacity-20 px-3 py-1 rounded-full">
                    {{ group.sections.length }} {{ group.sections.length === 1 ? 'Section' : 'Sections' }}
                  </span>
                </div>
                <svg
                  :class="expandedGroups[groupKey] ? 'rotate-180' : ''"
                  class="w-6 h-6 text-white transition-transform duration-200"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
              </button>

              <!-- Group Content -->
              <div
                v-show="expandedGroups[groupKey]"
                class="p-4 space-y-4"
              >
                <!-- Sections within Group -->
                <div
                  v-for="(section, sectionIndex) in group.sections"
                  :key="sectionIndex"
                  class="bg-gray-50 rounded-lg shadow-sm overflow-hidden border border-gray-200"
                >
                  <!-- Section Header -->
                  <button
                    :id="`section-${group.startIndex + sectionIndex}`"
                    @click="toggleSection(group.startIndex + sectionIndex)"
                    class="w-full px-6 py-3 bg-slate-50 hover:bg-white flex justify-between items-center transition-colors border-b border-gray-200"
                  >
                    <div class="flex flex-col items-start text-left">
                      <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-800">
                          {{ section.sectionTitle || section.title || section.section || `Section ${group.startIndex + sectionIndex + 1}` }}
                        </h3>
                        <!-- Completion Tick -->
                        <div v-if="sectionResults[group.startIndex + sectionIndex]" class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                          <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </div>
                      </div>
                      <div v-if="section.category" class="flex gap-2 mt-1">
                        <span class="text-xs px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] rounded">
                          {{ section.category }}
                        </span>
                      </div>
                    </div>
                    <svg
                      :class="expandedSections[group.startIndex + sectionIndex] ? 'rotate-180' : ''"
                      class="w-5 h-5 text-gray-600 transition-transform duration-200"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                  </button>

                  <!-- Section Content (Collapsible) -->
                  <div
                    v-show="expandedSections[group.startIndex + sectionIndex]"
                    class="px-6 py-6 space-y-6 bg-white"
                  >
                    <!-- Audio Player for Listening Category -->
                    <div v-if="section.audio && Array.isArray(section.audio) && section.audio.length > 0" class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                      <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M6.343 6.343l-.707-.707m0 0L5 4.586m1.636 1.636L6.343 6.343zm12.728 0l.707-.707m0 0L19 4.586m-1.636 1.636l.707.707zM6.343 17.657l-.707.707m0 0L5 19.414m1.636-1.636l-.707.707zm12.728 0l.707.707m0 0L19 19.414m-1.636-1.636l.707-.707z"/>
                        </svg>
                        Audio Files
                      </h4>
                      <div class="flex flex-wrap gap-3">
                        <div
                          v-for="(audioFile, audioIndex) in section.audio"
                          :key="audioIndex"
                          class="flex-1 min-w-[300px] bg-white rounded-lg p-3 border border-blue-200"
                        >
                          <p class="text-xs text-gray-600 mb-2 font-medium">Audio {{ audioIndex + 1 }}</p>
                          <audio
                            :src="getAudioUrl(audioFile)"
                            controls
                            controlsList="nodownload"
                            class="w-full h-10"
                            preload="none"
                          >
                            Your browser does not support the audio element.
                          </audio>
                        </div>
                      </div>
                    </div>

                    <!-- Reading Content (Collapsible for Listening category only) -->
                    <div v-if="(section.text || section.content || section.description) && course.course_category?.toLowerCase() === 'listening'" class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                      <button
                        @click="toggleTextSection(group.startIndex + sectionIndex)"
                        class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-100 transition-colors"
                      >
                        <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                          </svg>
                          Reading Text
                        </span>
                        <svg
                          class="w-5 h-5 text-gray-500 transition-transform duration-200"
                          :class="{ 'rotate-180': expandedTextSections[group.startIndex + sectionIndex] }"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                      </button>
                      <div
                        v-show="expandedTextSections[group.startIndex + sectionIndex]"
                        class="px-4 pb-4"
                      >
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                          {{ section.text || section.content || section.description }}
                        </p>
                      </div>
                    </div>

                    <!-- Reading Content (Normal for other categories, excluding Listening and Vocabulary) -->
                    <div v-if="(section.text || section.content || section.description) && !['listening', 'vocabulary'].includes(course.course_category?.toLowerCase())" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                      <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ section.text || section.content || section.description }}
                      </p>
                    </div>

                    <!-- Vocabulary Course Layout (Side-by-side Word Bank + Quiz) -->
                    <div v-if="course.course_category?.toLowerCase() === 'vocabulary' && (section.wordBank?.length > 0 || section.questions?.length > 0)" class="flex flex-col lg:flex-row gap-6">
                      <!-- Word Bank (Left Side) -->
                      <div class="lg:w-2/5 bg-white rounded-xl border border-gray-200 overflow-hidden h-fit">
                        <div class="px-4 py-3 flex items-center justify-between border-b border-gray-100">
                          <span class="text-base font-bold text-gray-800">Word Bank</span>
                          <button
                            @click="toggleWordBank(group.startIndex + sectionIndex)"
                            class="text-sm text-[#0055A4] hover:text-[#003d7a] flex items-center gap-1 font-medium"
                          >
                            {{ expandedWordBanks[group.startIndex + sectionIndex] ? 'Hide' : 'Show' }}
                            <svg
                              class="w-4 h-4 transition-transform duration-200"
                              :class="{ 'rotate-180': !expandedWordBanks[group.startIndex + sectionIndex] }"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                          </button>
                        </div>
                        <div
                          v-show="expandedWordBanks[group.startIndex + sectionIndex]"
                          class="p-4 space-y-2"
                        >
                          <div
                            v-for="(wordPair, wpIndex) in getWordPairs(section)"
                            :key="wpIndex"
                            class="flex items-center justify-between bg-gray-50 rounded-lg border border-gray-200 px-4 py-3"
                          >
                            <span class="text-gray-800 font-medium text-sm">{{ wordPair.word }}</span>
                            <span class="text-[#0055A4] font-medium text-sm">{{ wordPair.translation }}</span>
                          </div>
                        </div>
                      </div>

                      <!-- Quiz Section (Right Side) -->
                      <div class="lg:w-3/5 space-y-4">
                        <div
                          v-if="getVocabQuestions(section, group.startIndex + sectionIndex)[getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)]"
                          class="bg-white rounded-xl border border-gray-200 p-6"
                        >
                          <p class="font-semibold text-gray-800 mb-6 text-center text-lg">
                            {{ getVocabQuestions(section, group.startIndex + sectionIndex)[getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)].question }}
                          </p>

                          <div class="grid grid-cols-2 gap-3 mb-6">
                            <button
                              v-for="(option, oIndex) in (getVocabQuestions(section, group.startIndex + sectionIndex)[getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)].options || [])"
                              :key="oIndex"
                              @click="() => {
                                const vocabQs = getVocabQuestions(section, group.startIndex + sectionIndex);
                                const actualQIndex = getShuffledQuestionIndex(group.startIndex + sectionIndex, vocabQs.length);
                                if (!sectionResults[group.startIndex + sectionIndex] && !answeredQuestions[`${group.startIndex + sectionIndex}-${actualQIndex}`]) {
                                  userAnswers[`${group.startIndex + sectionIndex}-${actualQIndex}`] = oIndex;
                                  handleAnswerSelect(group.startIndex + sectionIndex, actualQIndex);
                                }
                              }"
                              :disabled="!!sectionResults[group.startIndex + sectionIndex] || !!answeredQuestions[`${group.startIndex + sectionIndex}-${getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)}`]"
                              class="p-4 rounded-xl border-2 text-center font-medium transition-all duration-200"
                              :class="[
                                isOptionSelected(group.startIndex + sectionIndex, getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length), oIndex)
                                  ? (sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)}`])
                                    ? isOptionCorrect(group.startIndex + sectionIndex, getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length), oIndex, getVocabQuestions(section, group.startIndex + sectionIndex))
                                      ? 'bg-green-100 border-green-500 text-green-700'
                                      : 'bg-red-100 border-red-500 text-red-700'
                                    : 'bg-[#0055A4]/20 border-[#0055A4] text-gray-800'
                                  : (sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)}`])
                                    ? isOptionCorrect(group.startIndex + sectionIndex, getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length), oIndex, getVocabQuestions(section, group.startIndex + sectionIndex))
                                      ? 'bg-green-50 border-green-400 text-green-700'
                                      : 'bg-gray-100 border-gray-200 text-gray-400'
                                    : 'bg-gray-100 border-gray-200 text-gray-700 hover:border-[#0055A4] hover:bg-[#0055A4]/5'
                              ]"
                            >
                              {{ typeof option === 'object' ? (option.text || option.option || '') : option }}
                            </button>
                          </div>

                          <div class="text-center text-gray-500 text-sm mb-4">
                            Question {{ getCurrentVocabQuestionIndex(group.startIndex + sectionIndex) + 1 }} of {{ getVocabQuestions(section, group.startIndex + sectionIndex).length }}
                          </div>

                          <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div
                              class="bg-[#0055A4] h-2 rounded-full transition-all duration-300"
                              :style="{ width: `${((getCurrentVocabQuestionIndex(group.startIndex + sectionIndex) + 1) / getVocabQuestions(section, group.startIndex + sectionIndex).length) * 100}%` }"
                            ></div>
                          </div>

                          <div v-if="!sectionResults[group.startIndex + sectionIndex]" class="flex justify-between items-center gap-3">
                            <button
                              @click="prevVocabQuestion(group.startIndex + sectionIndex)"
                              :disabled="getCurrentVocabQuestionIndex(group.startIndex + sectionIndex) === 0"
                              class="px-4 py-2 rounded-lg font-medium transition-all text-sm"
                              :class="getCurrentVocabQuestionIndex(group.startIndex + sectionIndex) === 0
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            >
                              Previous
                            </button>

                            <button
                              v-if="getCurrentVocabQuestionIndex(group.startIndex + sectionIndex) < getVocabQuestions(section, group.startIndex + sectionIndex).length - 1"
                              @click="nextVocabQuestion(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)"
                              :disabled="!answeredQuestions[`${group.startIndex + sectionIndex}-${getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)}`]"
                              class="px-4 py-2 rounded-lg font-medium transition-all text-sm"
                              :class="answeredQuestions[`${group.startIndex + sectionIndex}-${getShuffledQuestionIndex(group.startIndex + sectionIndex, getVocabQuestions(section, group.startIndex + sectionIndex).length)}`]
                                ? 'bg-[#0055A4] hover:bg-[#003d7a] text-white'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                            >
                              Next
                            </button>

                            <button
                              v-else
                              @click="finishSection(group.startIndex + sectionIndex)"
                              :disabled="!allQuestionsAnswered(group.startIndex + sectionIndex)"
                              :class="allQuestionsAnswered(group.startIndex + sectionIndex)
                                ? 'bg-[#0055A4] hover:bg-[#003d7a] text-white'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                              class="px-4 py-2 rounded-lg font-medium transition-all text-sm"
                            >
                              Complete Section
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Quiz Section (For Non-Vocabulary Categories) -->
                    <div v-if="course.course_category?.toLowerCase() !== 'vocabulary' && section.questions && section.questions.length > 0" class="space-y-6">
                      <div
                        v-for="(question, qIndex) in section.questions"
                        :key="qIndex"
                        class="bg-white rounded-xl border border-gray-100 p-4 md:p-6 transition-all duration-300"
                        :class="[
                          (sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]) ? 'shadow-sm' : 'shadow-md shadow-gray-100/50'
                        ]"
                      >
                        <p class="font-semibold text-gray-800 mb-4 text-sm md:text-base leading-snug">
                          {{ qIndex + 1 }}. {{ question.question || question.text || `Question ${qIndex + 1}` }}
                        </p>

                        <!-- Answer Options -->
                        <div class="space-y-3">
                          <label
                            v-for="(option, oIndex) in (question.options || [])"
                            :key="oIndex"
                            class="relative flex items-center p-3 md:p-4 rounded-xl border-2 transition-all duration-300"
                            :class="[
                              getOptionClass(group.startIndex + sectionIndex, qIndex, oIndex, section.questions),
                              !(sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]) ? 'hover:border-[#0055A4] hover:bg-[#0055A4]/5 cursor-pointer' : 'cursor-default'
                            ]"
                          >
                            <!-- Custom Radio Indicator -->
                            <div
                              class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all duration-300 flex-shrink-0"
                              :class="getRadioClass(group.startIndex + sectionIndex, qIndex, oIndex, section.questions)"
                            >
                              <!-- Radio Dot (Pre-answer) -->
                              <div v-if="!(sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]) && isOptionSelected(group.startIndex + sectionIndex, qIndex, oIndex)" class="w-2.5 h-2.5 rounded-full bg-white"></div>

                              <!-- Status Icons (Post-answer) -->
                              <template v-if="sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]">
                                <svg v-if="isOptionCorrect(group.startIndex + sectionIndex, qIndex, oIndex, section.questions)" class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg v-else-if="isOptionSelected(group.startIndex + sectionIndex, qIndex, oIndex)" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                              </template>
                            </div>

                            <input
                              type="radio"
                              :name="`question-${group.startIndex + sectionIndex}-${qIndex}`"
                              :value="oIndex"
                              v-model.number="userAnswers[`${group.startIndex + sectionIndex}-${qIndex}`]"
                              @change="handleAnswerSelect(group.startIndex + sectionIndex, qIndex)"
                              :disabled="!!sectionResults[group.startIndex + sectionIndex] || !!answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]"
                              class="sr-only"
                            />

                            <span
                              class="ml-4 font-normal transition-colors duration-300"
                              :class="(sectionResults[group.startIndex + sectionIndex] || answeredQuestions[`${group.startIndex + sectionIndex}-${qIndex}`]) ? (isOptionCorrect(group.startIndex + sectionIndex, qIndex, oIndex, section.questions) || isOptionSelected(group.startIndex + sectionIndex, qIndex, oIndex) ? 'text-gray-900 font-medium' : 'text-gray-400') : 'text-gray-600'"
                            >
                              {{ typeof option === 'object' ? (option.text || option.option || '') : option }}
                            </span>
                          </label>
                        </div>
                      </div>

                      <!-- Action Buttons (Before Finish) -->
                      <div v-if="!sectionResults[group.startIndex + sectionIndex]" class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-gray-100">
                        <button
                          @click="clearAnswers(group.startIndex + sectionIndex)"
                          class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-medium transition-all"
                        >
                          Clear Answers
                        </button>
                        <button
                          @click="finishSection(group.startIndex + sectionIndex)"
                          :disabled="!allQuestionsAnswered(group.startIndex + sectionIndex)"
                          :class="allQuestionsAnswered(group.startIndex + sectionIndex)
                            ? 'bg-[#0055A4] hover:bg-[#003d7a] text-white shadow-lg shadow-[#0055A4]/20'
                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                          class="w-full sm:w-auto px-8 py-2.5 rounded-xl font-medium transition-all sm:ml-auto"
                        >
                          Finish Section
                        </button>
                      </div>

                      <!-- Results Display (After Finish) -->
                      <div v-if="!!sectionResults[group.startIndex + sectionIndex]" class="space-y-4 pt-6 mt-6 border-t border-gray-100">
                        <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-100 rounded-2xl p-6 md:p-8 shadow-xl shadow-gray-200/50 text-center relative overflow-hidden">
                          <p class="text-lg font-semibold text-gray-800 mb-6">Section result summary</p>

                          <!-- Score Boxes -->
                          <div class="grid grid-cols-3 gap-3 md:gap-6 mb-8 max-w-xl mx-auto text-center">
                            <div class="bg-green-50 rounded-2xl p-3 md:p-5 border border-green-100">
                              <p class="text-green-600 text-[10px] md:text-xs font-bold uppercase tracking-tight mb-1">Correct</p>
                              <p class="text-2xl md:text-3xl font-semibold text-green-700 leading-none">
                                {{ sectionResults[group.startIndex + sectionIndex].correct }}
                              </p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-3 md:p-5 border border-gray-200">
                              <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-tight mb-1">Total</p>
                              <p class="text-2xl md:text-3xl font-semibold text-gray-800 leading-none">
                                {{ sectionResults[group.startIndex + sectionIndex].total }}
                              </p>
                            </div>
                            <div class="bg-[#0055A4]/10 rounded-2xl p-3 md:p-5 border border-[#0055A4]/20">
                              <p class="text-[#003d7a] text-[10px] md:text-xs font-bold uppercase tracking-tight mb-1">Score</p>
                              <p class="text-2xl md:text-3xl font-semibold text-[#0055A4] leading-none">
                                {{ sectionResults[group.startIndex + sectionIndex].percentage }}%
                              </p>
                            </div>
                          </div>
                        </div>

                      <!-- Result Buttons -->
                      <div class="flex gap-4">
                        <button
                          @click="retrySection(group.startIndex + sectionIndex)"
                          class="flex-1 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition-colors"
                        >
                          Try Again
                        </button>
                        <button
                          @click="nextSection(group.startIndex + sectionIndex)"
                          v-if="group.startIndex + sectionIndex < courseSections.length - 1"
                          class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
                        >
                          Next Section
                        </button>
                        <button
                          v-else
                          @click="showCompletionModal = true"
                          class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors"
                        >
                          Complete Course
                        </button>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar (Right side) -->
        <div class="w-80 p-8">
          <div class="bg-white rounded-lg shadow p-6 sticky top-8">
            <!-- Course Progress Tracker -->
            <h3 class="text-lg font-bold text-gray-800 mb-4">Progress</h3>

            <div class="mb-6">
              <p class="text-2xl font-bold text-[#0055A4]">{{ completedSections }}/{{ courseSections.length }}</p>
              <p class="text-gray-600 text-sm">Sections Completed</p>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
              <div
                :style="{ width: progressPercentage + '%' }"
                class="bg-gradient-to-r from-[#0055A4] to-[#003d7a] h-full transition-all duration-300"
              ></div>
            </div>
            <p class="text-gray-600 text-sm mt-2">{{ progressPercentage }}% Complete</p>

            <!-- Section Status List -->
            <div class="mt-6 space-y-2 border-t pt-6">
              <div
                v-for="(section, index) in courseSections"
                :key="index"
                class="flex items-center justify-between p-2 rounded hover:bg-gray-50"
              >
                <span class="text-sm text-gray-700">{{ section.sectionTitle || section.title || section.section || `Section ${index + 1}` }}</span>
                <span v-if="sectionResults[index]" class="text-green-600 font-bold">✓</span>
                <span v-else class="text-gray-400">○</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Completion Modal -->
      <div v-if="showCompletionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center">
          <div class="text-6xl mb-6">🏆</div>
          <h2 class="text-3xl font-bold text-gray-800 mb-4">Congratulations!</h2>
          <p class="text-gray-600 mb-6">You have successfully completed the course!</p>

          <!-- Final Score Summary -->
          <div class="bg-[#0055A4]/10 rounded-lg p-6 mb-6">
            <p class="text-gray-700 text-sm font-semibold mb-2">Final Score</p>
            <p class="text-4xl font-bold text-[#0055A4]">{{ overallScore }}%</p>
            <p class="text-gray-600 text-sm mt-2">{{ courseSections.length }} Sections Completed</p>
          </div>

          <!-- Action Buttons -->
          <div class="space-y-3">
            <button
              @click="goBackToCourses"
              class="w-full px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
            >
              Back to Courses
            </button>
            <button
              @click="goToDashboard"
              class="w-full px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Go to Dashboard
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="flex items-center justify-center h-screen">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Course Not Found</h1>
        <p class="text-gray-600 mb-6">The course you're looking for doesn't exist.</p>
        <button
          @click="goBackToCourses"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          Back to Courses
        </button>
      </div>
    </div>
    </div><!-- /.pt-20 -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { Loader } from 'lucide-vue-next'
import { useAuthStore } from '../stores/auth'
import { useSettingsStore } from '../stores/settings'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const settingsStore = useSettingsStore()

const logoUrl = computed(() => {
  return settingsStore.siteLogo
    ? `${import.meta.env.VITE_API_URL}/storage/${settingsStore.siteLogo}`
    : ''
})

const backRootPath = computed(() => {
  const p = route.path || ''
  if (p.startsWith('/student/')) return '/student/courses'
  if (p.startsWith('/tutor/')) return '/tutor/courses'
  return '/admin/courses'
})

const accountPath = computed(() => {
  const t = auth.user?.user_type
  if (t === 'tutor') return '/tutor/account'
  if (t === 'admin' || t === 'superadmin') return '/admin/dashboard'
  return '/student/account'
})

const isLoggingOut = ref(false)
const logout = async () => {
  if (isLoggingOut.value) return
  isLoggingOut.value = true
  try {
    await auth.logout()
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    await axios.post('/logout', { _token: csrfToken })
    window.location.href = '/'
  } catch (error) {
    console.error('Logout error:', error)
    window.location.href = '/'
  }
}

const loading = ref(true)
const course = ref(null)
const courseSections = ref([])
const expandedSections = ref({})
const expandedGroups = ref({})
const expandedTextSections = ref({})
const expandedWordBanks = ref({})
const currentVocabQuestion = ref({}) // Track current question index for each vocabulary section
const shuffledVocabQuestions = ref({}) // Store shuffled question indices for each vocabulary section
const generatedVocabQuestions = ref({}) // Cache for generated vocabulary questions
const userAnswers = ref({})
const answeredQuestions = ref({})
const sectionResults = ref({})
const showCompletionModal = ref(false)

const courseId = route.params.id

// Helper function to get audio URL
const getAudioUrl = (audioPath) => {
  if (!audioPath) return ''
  
  // If it's already a full URL, return it
  if (audioPath.startsWith('http://') || audioPath.startsWith('https://')) {
    return audioPath
  }
  
  // If it starts with /storage, return as is
  if (audioPath.startsWith('/storage/')) {
    return audioPath
  }
  
  // If path starts with listening/audio, add courses/ prefix
  if (audioPath.startsWith('listening/audio/')) {
    return `/storage/courses/${audioPath}`
  }
  
  // Otherwise, prepend /storage/
  return `/storage/${audioPath}`
}

// Helper function to get full image URL
const getBannerImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  const apiURL = import.meta.env.VITE_API_URL
  // If path is relative (e.g., 'courses/banners/filename.jpg'), prepend /storage/
  if (!imagePath.startsWith('/')) {
    return apiURL + '/storage/' + imagePath
  }
  return apiURL + imagePath
}

// Group sections by difficulty/level
const groupedSections = computed(() => {
  const grouped = {}
  let currentIndex = 0
  
  courseSections.value.forEach((section) => {
    // Use difficulty, level, or fallback to 'Other Sections'
    const groupKey = section.difficulty || section.level || 'Other Sections'
    
    if (!grouped[groupKey]) {
      grouped[groupKey] = {
        sections: [],
        startIndex: currentIndex
      }
      // Initialize group expansion state if not already set
      if (expandedGroups.value[groupKey] === undefined) {
        expandedGroups.value[groupKey] = Object.keys(grouped).length === 1 // Expand first group by default
      }
    }
    
    grouped[groupKey].sections.push(section)
    currentIndex++
  })
  
  // Sort groups: A1, A2, B1, B2, C1, C2 first, then others
  const sortedKeys = Object.keys(grouped).sort((a, b) => {
    const levelOrder = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2']
    const aIndex = levelOrder.indexOf(a)
    const bIndex = levelOrder.indexOf(b)
    
    if (aIndex !== -1 && bIndex !== -1) return aIndex - bIndex
    if (aIndex !== -1) return -1
    if (bIndex !== -1) return 1
    return a.localeCompare(b)
  })
  
  const sortedGroups = {}
  sortedKeys.forEach(key => {
    sortedGroups[key] = grouped[key]
  })
  
  return sortedGroups
})

// Get course data from API
const fetchCourse = async () => {
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const fullUrl = apiURL + `/api/courses/${courseId}`
    const response = await fetch(fullUrl)

    if (response.ok) {
      const data = await response.json()
      const courseData = data.data || data
      
      // Check if this is a books or lingopie course - redirect to custom_url immediately
      if ((courseData.course_category === 'books' || courseData.course_category === 'lingopie') && courseData.custom_url) {
        const target = courseData.custom_url_target || '_blank'
        if (target === '_blank') {
          window.open(courseData.custom_url, '_blank')
        } else {
          window.location.href = courseData.custom_url
        }
        return
      }
      
      course.value = courseData

      // Parse course content from JSON
      try {
        let jsonContent = course.value.course_json_content

        // If it's a string, parse it
        if (typeof jsonContent === 'string') {
          jsonContent = JSON.parse(jsonContent)
        }

        // Handle the new structure: course_json_content is an array of sections
        if (Array.isArray(jsonContent)) {
          // Process each section in the array
          const processedSections = []
          
          jsonContent.forEach((section, sectionIndex) => {
            // If section has 'activities' array (like Reading category structure)
            if (section.activities && Array.isArray(section.activities)) {
              // Each activity becomes a separate section
              section.activities.forEach((activity, activityIndex) => {
                processedSections.push({
                  ...activity,
                  sectionIndex: sectionIndex,
                  activityIndex: activityIndex,
                  category: section.category,
                  difficulty: section.difficulty,
                  // Use activity title or create a section title
                  sectionTitle: activity.title || `Section ${processedSections.length + 1}`,
                  // Questions come from the activity
                  questions: activity.questions || []
                })
              })
            } else {
              // Section doesn't have activities, treat it as a single section
              processedSections.push({
                ...section,
                sectionIndex: sectionIndex,
                // Use section title or create one
                sectionTitle: section.section || section.title || `Section ${processedSections.length + 1}`,
                // Questions might be directly in the section
                questions: section.questions || []
              })
            }
          })
          
          courseSections.value = processedSections
        } else if (jsonContent && jsonContent.activities && Array.isArray(jsonContent.activities)) {
          // Legacy format: single object with activities array
          courseSections.value = jsonContent.activities.map((activity, index) => ({
            ...activity,
            sectionTitle: activity.title || `Section ${index + 1}`,
            questions: activity.questions || []
          }))
        } else if (jsonContent) {
          // Single section object
          courseSections.value = [{
            ...jsonContent,
            sectionTitle: jsonContent.section || jsonContent.title || 'Section 1',
            questions: jsonContent.questions || []
          }]
        } else {
          courseSections.value = []
        }
        
      } catch (e) {
        console.error('Error parsing course content:', e)
        courseSections.value = []
      }

      // Initialize expanded sections (all collapsed by default)
      courseSections.value.forEach((_, index) => {
        expandedSections.value[index] = false
      })

      // Initialize expanded groups (expand first group by default)
      await nextTick()
      const firstGroupKey = Object.keys(groupedSections.value)[0]
      if (firstGroupKey) {
        expandedGroups.value[firstGroupKey] = true
      }

      // Load saved progress from database
      await loadProgress()

      // After loading progress, expand first non-completed section
      await nextTick()
      if (firstGroupKey && groupedSections.value[firstGroupKey].sections.length > 0) {
        const firstSectionIndex = groupedSections.value[firstGroupKey].startIndex
        // Only expand if section is not completed
        if (!sectionResults.value[firstSectionIndex]) {
          expandedSections.value[firstSectionIndex] = true
        }
      }
    } else {
      course.value = null
    }
  } catch (error) {
    console.error('Error fetching course:', error)
    course.value = null
  } finally {
    loading.value = false
  }
}

// Helper to get questions from section
const getDefaultQuestions = (sectionIndex) => {
  const section = courseSections.value[sectionIndex]
  if (section) {
    // Questions might be directly in the section
    if (section.questions && Array.isArray(section.questions)) {
      return section.questions
    }
    // Or might be nested in an activity structure
    if (section.activity && section.activity.questions && Array.isArray(section.activity.questions)) {
      return section.activity.questions
    }
  }
  return []
}

// Go back functionality
const goBack = () => {
  router.push('/admin/courses')
}

// Toggle section expansion
const toggleSection = (index) => {
  expandedSections.value[index] = !expandedSections.value[index]
}

// Toggle group expansion
const toggleGroup = (groupKey) => {
  expandedGroups.value[groupKey] = !expandedGroups.value[groupKey]
}

// Toggle text section expansion (for reading content)
const toggleTextSection = (index) => {
  expandedTextSections.value[index] = !expandedTextSections.value[index]
}

// Toggle word bank expansion (for vocabulary)
const toggleWordBank = (index) => {
  expandedWordBanks.value[index] = !expandedWordBanks.value[index]
}

// Shuffle array using Fisher-Yates algorithm
const shuffleArray = (array) => {
  const shuffled = [...array]
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    ;[shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]]
  }
  return shuffled
}

// Get or create shuffled question indices for a section
const getShuffledQuestionIndices = (sectionIndex, totalQuestions) => {
  if (!shuffledVocabQuestions.value[sectionIndex]) {
    const indices = Array.from({ length: totalQuestions }, (_, i) => i)
    shuffledVocabQuestions.value[sectionIndex] = shuffleArray(indices)
  }
  return shuffledVocabQuestions.value[sectionIndex]
}

// Get current vocabulary question index for a section
const getCurrentVocabQuestionIndex = (sectionIndex) => {
  return currentVocabQuestion.value[sectionIndex] || 0
}

// Get the actual question index from the shuffled order
const getShuffledQuestionIndex = (sectionIndex, totalQuestions) => {
  const shuffledIndices = getShuffledQuestionIndices(sectionIndex, totalQuestions)
  const currentPosition = getCurrentVocabQuestionIndex(sectionIndex)
  return shuffledIndices[currentPosition] ?? 0
}

// Navigate to next vocabulary question
const nextVocabQuestion = (sectionIndex, totalQuestions) => {
  const current = getCurrentVocabQuestionIndex(sectionIndex)
  if (current < totalQuestions - 1) {
    currentVocabQuestion.value[sectionIndex] = current + 1
  }
}

// Navigate to previous vocabulary question
const prevVocabQuestion = (sectionIndex) => {
  const current = getCurrentVocabQuestionIndex(sectionIndex)
  if (current > 0) {
    currentVocabQuestion.value[sectionIndex] = current - 1
  }
}

// Reset vocabulary question index for a section
const resetVocabQuestion = (sectionIndex) => {
  currentVocabQuestion.value[sectionIndex] = 0
  delete shuffledVocabQuestions.value[sectionIndex]
  delete generatedVocabQuestions.value[sectionIndex]
}

// Generate quiz questions from wordBank array
const getVocabQuestions = (section, sectionIndex) => {
  if (section.wordBank && Array.isArray(section.wordBank) && section.wordBank.length > 0) {
    if (generatedVocabQuestions.value[sectionIndex]) {
      return generatedVocabQuestions.value[sectionIndex]
    }

    const wordBank = section.wordBank
    const questions = wordBank.map((item, index) => {
      const word = item.english || item.word || item.term || ''
      const correctAnswer = item.french || item.translation || item.definition || ''

      const otherItems = wordBank.filter((_, i) => i !== index)
      const shuffledOthers = shuffleArray(otherItems).slice(0, 3)
      const wrongOptions = shuffledOthers.map(other =>
        other.french || other.translation || other.definition || ''
      )

      const allOptions = shuffleArray([correctAnswer, ...wrongOptions])
      const correctIndex = allOptions.indexOf(correctAnswer)

      return {
        question: `What is the French word for '${word}'?`,
        options: allOptions,
        correct_answer: correctIndex
      }
    })

    generatedVocabQuestions.value[sectionIndex] = questions
    return questions
  }

  return section.questions || []
}

// Extract word pairs for Word Bank - supports both wordBank array and questions
const getWordPairs = (section) => {
  // First, check if section has a dedicated wordBank array
  if (section.wordBank && Array.isArray(section.wordBank) && section.wordBank.length > 0) {
    return section.wordBank.map(item => ({
      word: item.english || item.word || item.term || '',
      translation: item.french || item.translation || item.definition || ''
    })).filter(pair => pair.word && pair.translation)
  }

  // Fallback: extract from questions array
  const questions = section.questions || section
  if (!questions || !Array.isArray(questions)) return []

  return questions.map(question => {
    const questionText = question.question || question.text || ''
    const options = question.options || []
    let correctAnswer = question.correct_answer ?? question.correctAnswer ?? question.answer ?? 0

    if (typeof correctAnswer === 'string') {
      const foundIndex = options.findIndex(opt => {
        const optText = typeof opt === 'object' ? (opt.text || opt.option || '') : opt
        return optText.toLowerCase() === correctAnswer.toLowerCase()
      })
      if (foundIndex !== -1) {
        correctAnswer = foundIndex
      } else {
        const parsed = parseInt(correctAnswer, 10)
        correctAnswer = isNaN(parsed) ? 0 : parsed
      }
    }

    let word = ''
    const patterns = [
      /['"]([^'"]+)['"]/,
      /for\s+["']?([^"'?]+)["']?\s*\?/i,
      /for\s+(\w+)\s*\?/i,
      /["']([^"']+)["']/,
    ]

    for (const pattern of patterns) {
      const match = questionText.match(pattern)
      if (match && match[1]) {
        word = match[1].trim()
        break
      }
    }

    if (!word) {
      word = questionText
        .replace(/^(What is the .+ word for |What is |Translate |How do you say )/i, '')
        .replace(/\?$/, '')
        .replace(/['"]/g, '')
        .trim()
    }

    let translation = ''
    if (options[correctAnswer] !== undefined) {
      const option = options[correctAnswer]
      translation = typeof option === 'object' ? (option.text || option.option || '') : option
    } else if (options.length > 0) {
      const option = options[0]
      translation = typeof option === 'object' ? (option.text || option.option || '') : option
    }

    return { word, translation }
  }).filter(pair => pair.word && pair.translation)
}

// Helper to get correct answer index
const getCorrectAnswerIndex = (sectionIndex, qIndex, questions) => {
  if (!questions || !questions[qIndex]) return -1
  const question = questions[qIndex]
  const ans = question.answer !== undefined ? question.answer :
             (question.correctAnswer !== undefined ? question.correctAnswer :
             (question.correct_answer !== undefined ? question.correct_answer : null))
  if (ans !== null && typeof ans === 'number') return ans
  return -1
}

// Load progress from database
const loadProgress = async () => {
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const token = localStorage.getItem('token')

    if (!token) return

    const response = await fetch(`${apiURL}/api/admin/preview-progress/${courseId}`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (response.ok) {
      const data = await response.json()

      // Restore answered questions and user answers
      if (data.answeredQuestions && Object.keys(data.answeredQuestions).length > 0) {
        for (const [key, value] of Object.entries(data.answeredQuestions)) {
          answeredQuestions.value[key] = value
          userAnswers.value[key] = parseInt(value.selectedAnswer)
        }
      }

      // Restore section results (for tick marks)
      if (data.sectionResults && Object.keys(data.sectionResults).length > 0) {
        for (const [key, value] of Object.entries(data.sectionResults)) {
          sectionResults.value[key] = value
        }
      }
    }
  } catch (error) {
    console.error('Error loading progress:', error)
  }
}

// Status helpers for quiz UI
const isOptionSelected = (sectionIndex, qIndex, oIndex) => {
  return userAnswers.value[`${sectionIndex}-${qIndex}`] === oIndex
}

const isOptionCorrect = (sectionIndex, qIndex, oIndex, questions) => {
  return getCorrectAnswerIndex(sectionIndex, qIndex, questions) === oIndex
}

const handleAnswerSelect = async (sectionIndex, qIndex) => {
  const key = `${sectionIndex}-${qIndex}`
  const selectedAnswerIndex = userAnswers.value[key]
  const questions = courseSections.value[sectionIndex]?.questions || getDefaultQuestions(sectionIndex)
  const correctIndex = getCorrectAnswerIndex(sectionIndex, qIndex, questions)
  const isCorrect = selectedAnswerIndex === correctIndex

  answeredQuestions.value[key] = {
    selectedAnswer: String(selectedAnswerIndex),
    isCorrect: isCorrect
  }

  // Save progress to database
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const token = localStorage.getItem('token')

    if (!token) return

    await fetch(`${apiURL}/api/admin/preview-progress/${courseId}/section`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        section_index: sectionIndex,
        question_index: qIndex,
        selected_answer: String(selectedAnswerIndex),
        is_correct: isCorrect
      })
    })
  } catch (error) {
    console.error('Error saving progress:', error)
  }
}

const getOptionClass = (sectionIndex, qIndex, oIndex, questions) => {
  const isAnswered = !!answeredQuestions.value[`${sectionIndex}-${qIndex}`] || !!sectionResults.value[sectionIndex]
  const isSelected = isOptionSelected(sectionIndex, qIndex, oIndex)
  const isCorrect = isOptionCorrect(sectionIndex, qIndex, oIndex, questions)

  if (!isAnswered) {
    return isSelected
      ? 'border-[#0055A4] bg-[#0055A4]/5'
      : 'border-gray-100 bg-white shadow-sm shadow-gray-100/50'
  }

  if (isCorrect) {
    return isSelected
      ? 'border-green-500 bg-green-50'
      : 'border-green-500/30 bg-green-50/10'
  }

  if (isSelected) {
    return 'border-red-500 bg-red-50'
  }

  return 'border-gray-50 bg-gray-50/30 opacity-60'
}

const getRadioClass = (sectionIndex, qIndex, oIndex, questions) => {
  const isAnswered = !!answeredQuestions.value[`${sectionIndex}-${qIndex}`] || !!sectionResults.value[sectionIndex]
  const isSelected = isOptionSelected(sectionIndex, qIndex, oIndex)
  const isCorrect = isOptionCorrect(sectionIndex, qIndex, oIndex, questions)

  if (!isAnswered) {
    return isSelected
      ? 'border-[#0055A4] bg-[#0055A4]'
      : 'border-gray-300 bg-white'
  }

  if (isCorrect) {
    return 'border-green-500 bg-green-50'
  }

  if (isSelected) {
    return 'border-red-500 bg-red-50'
  }

  return 'border-gray-200 bg-gray-50'
}

// Clear answers for a section
const clearAnswers = (index) => {
  const section = courseSections.value[index]

  // For vocabulary courses, use generated questions from wordBank
  let sectionQuestions
  if (course.value?.course_category?.toLowerCase() === 'vocabulary' && section?.wordBank?.length > 0) {
    sectionQuestions = getVocabQuestions(section, index)
  } else {
    sectionQuestions = section?.questions || getDefaultQuestions(index)
  }

  sectionQuestions.forEach((_, qIndex) => {
    delete userAnswers.value[`${index}-${qIndex}`]
    delete answeredQuestions.value[`${index}-${qIndex}`]
  })
}

// Finish section and calculate score
const finishSection = (index) => {
  const section = courseSections.value[index]

  // For vocabulary courses, use generated questions from wordBank
  let sectionQuestions
  if (course.value?.course_category?.toLowerCase() === 'vocabulary' && section?.wordBank?.length > 0) {
    sectionQuestions = getVocabQuestions(section, index)
  } else {
    sectionQuestions = section?.questions || getDefaultQuestions(index)
  }

  let correct = 0

  sectionQuestions.forEach((question, qIndex) => {
    const userAnswer = userAnswers.value[`${index}-${qIndex}`]

    // Get the correct answer (could be stored as 'answer', 'correctAnswer', or 'correct_answer')
    const correctAnswerValue = question.answer ?? question.correctAnswer ?? question.correct_answer ?? null

    // Get the user's selected option text
    const userSelectedOption = question.options && question.options[userAnswer]

    // Compare texts if answer is stored as text, or compare indices
    if (correctAnswerValue !== null) {
      if (typeof correctAnswerValue === 'number') {
        // If correct answer is stored as index
        if (userAnswer === correctAnswerValue) {
          correct++
        }
      } else {
        // If correct answer is stored as text
        const userText = typeof userSelectedOption === 'object' ? userSelectedOption.text : userSelectedOption
        if (userText === correctAnswerValue) {
          correct++
        }
      }
    }
  })

  const total = sectionQuestions.length
  const percentage = total > 0 ? Math.round((correct / total) * 100) : 0

  sectionResults.value[index] = {
    correct,
    total,
    percentage
  }

  // Save section results to database
  saveSectionResults()
}

// Save section results to database
const saveSectionResults = async () => {
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const token = localStorage.getItem('token')

    if (!token) return

    await fetch(`${apiURL}/api/admin/preview-progress/${courseId}/results`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        section_results: sectionResults.value
      })
    })
  } catch (error) {
    console.error('Error saving section results:', error)
  }
}

// Retry section
const retrySection = (index) => {
  delete sectionResults.value[index]
  clearAnswers(index)
  saveSectionResults() // Save updated results
}

// Check if all questions in a section are answered
const allQuestionsAnswered = (sectionIndex) => {
  const section = courseSections.value[sectionIndex]

  // For vocabulary courses, use generated questions from wordBank
  let questions
  if (course.value?.course_category?.toLowerCase() === 'vocabulary' && section?.wordBank?.length > 0) {
    questions = getVocabQuestions(section, sectionIndex)
  } else {
    questions = section?.questions || getDefaultQuestions(sectionIndex)
  }

  if (!questions || questions.length === 0) return false

  for (let qIndex = 0; qIndex < questions.length; qIndex++) {
    if (userAnswers.value[`${sectionIndex}-${qIndex}`] === undefined) {
      return false
    }
  }
  return true
}

// Next section
const nextSection = (index) => {
  if (index < courseSections.value.length - 1) {
    const nextIndex = index + 1

    // Collapse current section
    expandedSections.value[index] = false

    // Expand next section
    expandedSections.value[nextIndex] = true

    // Scroll to next section after a short delay to allow DOM update
    nextTick(() => {
      const nextSectionEl = document.getElementById(`section-${nextIndex}`)
      if (nextSectionEl) {
        nextSectionEl.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    })
  }
}

// Computed properties
const completedSections = computed(() => {
  return Object.keys(sectionResults.value).length
})

const progressPercentage = computed(() => {
  if (courseSections.value.length === 0) return 0
  return Math.round((completedSections.value / courseSections.value.length) * 100)
})

const overallScore = computed(() => {
  if (Object.keys(sectionResults.value).length === 0) return 0

  let totalCorrect = 0
  let totalQuestions = 0

  Object.values(sectionResults.value).forEach(result => {
    totalCorrect += result.correct
    totalQuestions += result.total
  })

  return totalQuestions > 0 ? Math.round((totalCorrect / totalQuestions) * 100) : 0
})

// Navigation functions
const goBackToCourses = () => {
  router.push('/student/courses')
}

const goToDashboard = () => {
  router.push('/student/dashboard')
}

onMounted(() => {
  settingsStore.fetchSettings()
  fetchCourse()
})
</script>

<style scoped>
/* Smooth transitions */
* {
  transition: background-color 0.2s ease, border-color 0.2s ease;
}
</style>
