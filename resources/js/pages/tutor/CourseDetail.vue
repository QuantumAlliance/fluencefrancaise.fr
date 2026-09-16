<template>
  <div class="min-h-screen bg-gray-50">
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
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ course.course_title }}</h2>
            <p class="text-gray-600 mb-4">{{ course.course_subtitle }}</p>
            <p class="text-gray-700 leading-relaxed">{{ course.course_description }}</p>

            <!-- Course Meta Info -->
            <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
              <div>
                <p class="text-gray-500 text-sm font-semibold">Category</p>
                <p class="text-gray-800 font-medium">{{ course.course_category || '-' }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Language</p>
                <p class="text-gray-800 font-medium">{{ course.course_language || '-' }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Level</p>
                <p class="text-gray-800 font-medium">{{ course.course_level || '-' }}</p>
              </div>
              <div>
                <p class="text-gray-500 text-sm font-semibold">Total Readings</p>
                <p class="text-gray-800 font-medium">{{ course.course_total_texts || 0 }}</p>
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
                  <h2 class="text-xl font-semibold text-white">
                    {{ groupKey || 'Other Sections' }}
                  </h2>
                  <span class="text-sm text-white/90 bg-white bg-opacity-20 px-3 py-1 rounded-full">
                    {{ group.sections.length }} {{ group.sections.length === 1 ? 'Section' : 'Sections' }}
                  </span>
                </div>
                <div class="flex items-center gap-4">
                  <button 
                    @click.stop="resetGroupAnswers(groupKey)"
                    class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg transition-colors border border-white/20"
                    title="Reset all progress in this level"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Level
                  </button>
                  <svg
                    :class="expandedGroups[groupKey] ? 'rotate-180' : ''"
                    class="w-6 h-6 text-white transition-transform duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                  </svg>
                </div>
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
                    :data-section-index="group.startIndex + sectionIndex"
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
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M6.343 6.343l-.707-.707m0 0L5 4.586m1.636 1.636L6.343 6.343zm12.728 0l.707-.707m0 0L19 4.586m-1.636 1.636l.707.707zM6.343 17.657l-.707.707m0 0L5 19.414m1.636-1.636l.707.707zm12.728 0l.707.707m0 0L19 19.414m-1.636-1.636l.707-.707z"/>
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

                    <!-- Reading Content (Collapsible for Listening category) -->
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
                        <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">
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
                              @click="finishVocabSection(group.startIndex + sectionIndex, section)"
                              :disabled="!allVocabQuestionsAnswered(group.startIndex + sectionIndex, section)"
                              :class="allVocabQuestionsAnswered(group.startIndex + sectionIndex, section)
                                ? 'bg-[#0055A4] hover:bg-[#003d7a] text-white'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                              class="px-4 py-2 rounded-lg font-medium transition-all text-sm"
                            >
                              Complete Section
                            </button>
                          </div>
                        </div>

                        <!-- Results Display for Vocabulary -->
                        <div v-if="!!sectionResults[group.startIndex + sectionIndex]" class="bg-white rounded-xl border border-gray-200 p-6">
                          <div class="text-center">
                            <p class="text-lg font-semibold text-gray-800 mb-6">Section Complete!</p>
                            <div class="grid grid-cols-3 gap-4 mb-6">
                              <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                <p class="text-green-600 text-xs font-bold uppercase mb-1">Correct</p>
                                <p class="text-2xl font-semibold text-green-700">{{ sectionResults[group.startIndex + sectionIndex].correct }}</p>
                              </div>
                              <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <p class="text-gray-400 text-xs font-bold uppercase mb-1">Total</p>
                                <p class="text-2xl font-semibold text-gray-800">{{ sectionResults[group.startIndex + sectionIndex].total }}</p>
                              </div>
                              <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                                <p class="text-[#0055A4] text-xs font-bold uppercase mb-1">Score</p>
                                <p class="text-2xl font-semibold text-[#0055A4]">{{ sectionResults[group.startIndex + sectionIndex].percentage }}%</p>
                              </div>
                            </div>
                            <button
                              @click="retryVocabSection(group.startIndex + sectionIndex, section)"
                              class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-all"
                            >
                              Try Again
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
                          @click="clearAnswers(group.startIndex + sectionIndex, section.questions)"
                          class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-medium transition-all"
                        >
                          Clear Answers
                        </button>
                        <button
                          @click="finishSection(group.startIndex + sectionIndex, section.questions)"
                          class="w-full sm:w-auto px-8 py-2.5 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-xl font-medium transition-all shadow-lg shadow-[#0055A4]/20 sm:ml-auto"
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
                            <div class="bg-orange-50 rounded-2xl p-3 md:p-5 border border-orange-100">
                              <p class="text-[#0055A4] text-[10px] md:text-xs font-bold uppercase tracking-tight mb-1">Score</p>
                              <p class="text-2xl md:text-3xl font-semibold text-[#0055A4] leading-none">
                                {{ sectionResults[group.startIndex + sectionIndex].percentage }}%
                              </p>
                            </div>
                          </div>
                        </div>

                        <!-- Result Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                          <button
                            @click="retrySection(group.startIndex + sectionIndex, section.questions)"
                            class="flex-1 px-8 py-3.5 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-500 rounded-2xl font-medium transition-all flex items-center justify-center gap-2"
                          >
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Try Again</span>
                          </button>
                          <button
                            @click="nextSection(group.startIndex + sectionIndex)"
                            v-if="group.startIndex + sectionIndex < courseSections.length - 1"
                            class="flex-1 px-8 py-3.5 bg-gray-900 hover:bg-black text-white rounded-2xl font-medium transition-all shadow-xl shadow-gray-200 flex items-center justify-center gap-2 group"
                          >
                            <span>Next Lesson</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
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
          <div class="bg-white rounded-lg shadow p-6 sticky top-8 border border-gray-100">
            <!-- Course Progress Tracker -->
            <div v-if="course.course_category !== 'books' && course.course_category !== 'lingopie'">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress</h3>

              <div class="mb-6">
                <p class="text-2xl font-semibold text-[#0055A4] tracking-tight">{{ courseSections.length }}</p>
                <p class="text-gray-600 text-sm">Total Sections</p>
              </div>

              <!-- Progress Bar (Static for tutors - showing all sections) -->
              <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div
                  class="bg-gradient-to-r from-[#0055A4] to-[#003d7a] h-full transition-all duration-300"
                  style="width: 100%"
                ></div>
              </div>
              <p class="text-gray-600 text-sm mt-2">All Sections Available</p>
            </div>

            <!-- Section Status List -->
            <div class="mt-6 space-y-2 border-t border-gray-100 pt-6 max-h-96 overflow-y-auto custom-scrollbar">
              <div
                v-for="(section, index) in courseSections"
                :key="index"
                @click="scrollToSection(index)"
                class="flex items-center justify-between p-2 rounded hover:bg-gray-50 cursor-pointer transition-colors group"
              >
                <span class="text-sm text-gray-700 group-hover:text-[#0055A4] transition-colors line-clamp-1">{{ section.sectionTitle || section.title || section.section || `Section ${index + 1}` }}</span>
                <span v-if="sectionResults[index]" class="text-green-500 font-bold">✓</span>
                <span v-else class="text-gray-300 group-hover:text-gray-400">○</span>
              </div>
            </div>
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
          @click="goBack"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          Back to Courses
        </button>
      </div>
    </div>

    <!-- Custom Reset Confirmation Modal -->
    <div v-if="showResetModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[100] p-4 font-sans">
      <div class="bg-white border border-gray-100 rounded-[32px] p-8 max-w-[380px] w-full text-gray-900 shadow-2xl shadow-black/5 animate-in fade-in zoom-in duration-300">
        <p class="text-[18px] font-semibold text-center mb-8 leading-relaxed px-2">
          Are you sure you want to reset all progress in the {{ resetTargetGroup }} level?
        </p>
        <div class="flex gap-4">
          <button 
            @click="confirmReset"
            class="flex-1 bg-[#0055A4] hover:bg-[#003d7a] text-white py-4 px-6 rounded-full font-bold text-sm active:scale-95 transition-all shadow-lg shadow-[#0055A4]/20"
          >
            OK
          </button>
          <button 
            @click="showResetModal = false"
            class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-4 px-6 rounded-full font-bold text-sm border border-gray-200 active:scale-95 transition-all"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const course = ref(null)
const courseSections = ref([])
const expandedSections = ref({})
const expandedGroups = ref({})
const userAnswers = ref({})
const answeredQuestions = ref({})
const sectionResults = ref({})
const showResetModal = ref(false)
const resetTargetGroup = ref('')

// Vocabulary course state
const expandedWordBanks = ref({})
const expandedTextSections = ref({})
const currentVocabQuestion = ref({})
const shuffledVocabQuestions = ref({})
const generatedVocabQuestions = ref({})

// Toggle reading text section
const toggleTextSection = (sectionIndex) => {
  expandedTextSections.value[sectionIndex] = !expandedTextSections.value[sectionIndex]
}

// Shuffle array helper (Fisher-Yates)
const shuffleArray = (array) => {
  const shuffled = [...array]
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]]
  }
  return shuffled
}

// Toggle word bank expansion
const toggleWordBank = (sectionIndex) => {
  expandedWordBanks.value[sectionIndex] = !expandedWordBanks.value[sectionIndex]
}

// Get word pairs from section
const getWordPairs = (section) => {
  if (section.wordBank && Array.isArray(section.wordBank) && section.wordBank.length > 0) {
    return section.wordBank.map(item => ({
      word: item.english || item.word || item.term || '',
      translation: item.french || item.translation || item.definition || ''
    }))
  }
  if (section.questions && Array.isArray(section.questions)) {
    return section.questions.map(q => ({
      word: q.word || q.term || q.question || '',
      translation: q.translation || q.answer || q.correctAnswer || ''
    }))
  }
  return []
}

// Generate vocabulary questions from wordBank
const getVocabQuestions = (section, sectionIndex) => {
  const cacheKey = `vocab-${sectionIndex}`
  if (generatedVocabQuestions.value[cacheKey]) {
    return generatedVocabQuestions.value[cacheKey]
  }

  if (section.wordBank && Array.isArray(section.wordBank) && section.wordBank.length > 0) {
    const wordBank = section.wordBank
    const questions = wordBank.map((item, index) => {
      const word = item.english || item.word || item.term || ''
      const correctAnswer = item.french || item.translation || item.definition || ''

      const otherAnswers = wordBank
        .filter((_, i) => i !== index)
        .map(w => w.french || w.translation || w.definition || '')

      const shuffledWrong = shuffleArray(otherAnswers).slice(0, 3)
      const allOptions = shuffleArray([correctAnswer, ...shuffledWrong])
      const correctIndex = allOptions.indexOf(correctAnswer)

      return {
        question: `What is the French word for "${word}"?`,
        options: allOptions,
        correct_answer: correctIndex
      }
    })

    generatedVocabQuestions.value[cacheKey] = questions
    return questions
  }

  return section.questions || []
}

// Get current vocabulary question index
const getCurrentVocabQuestionIndex = (sectionIndex) => {
  return currentVocabQuestion.value[sectionIndex] || 0
}

// Get shuffled question index
const getShuffledQuestionIndex = (sectionIndex, totalQuestions) => {
  if (!shuffledVocabQuestions.value[sectionIndex]) {
    const indices = Array.from({ length: totalQuestions }, (_, i) => i)
    shuffledVocabQuestions.value[sectionIndex] = shuffleArray(indices)
  }
  const currentIndex = currentVocabQuestion.value[sectionIndex] || 0
  return shuffledVocabQuestions.value[sectionIndex][currentIndex] || 0
}

// Navigate vocabulary questions
const nextVocabQuestion = (sectionIndex, totalQuestions) => {
  const current = currentVocabQuestion.value[sectionIndex] || 0
  if (current < totalQuestions - 1) {
    currentVocabQuestion.value[sectionIndex] = current + 1
  }
}

const prevVocabQuestion = (sectionIndex) => {
  const current = currentVocabQuestion.value[sectionIndex] || 0
  if (current > 0) {
    currentVocabQuestion.value[sectionIndex] = current - 1
  }
}

// Check if all vocabulary questions are answered
const allVocabQuestionsAnswered = (sectionIndex, section) => {
  const questions = getVocabQuestions(section, sectionIndex)
  for (let i = 0; i < questions.length; i++) {
    if (userAnswers.value[`${sectionIndex}-${i}`] === undefined) {
      return false
    }
  }
  return true
}

// Finish vocabulary section
const finishVocabSection = async (sectionIndex, section) => {
  const questions = getVocabQuestions(section, sectionIndex)
  let correct = 0

  questions.forEach((question, qIndex) => {
    const userAnswer = userAnswers.value[`${sectionIndex}-${qIndex}`]
    const correctIndex = question.correct_answer !== undefined ? question.correct_answer :
                        getCorrectAnswerIndex(sectionIndex, qIndex, questions)
    if (userAnswer === correctIndex && correctIndex !== -1) {
      correct++
    }
  })

  const total = questions.length
  const percentage = total > 0 ? Math.round((correct / total) * 100) : 0

  sectionResults.value[sectionIndex] = { correct, total, percentage }

  try {
    await axios.post(`/api/tutor/progress/${courseId}/section`, {
      activity_id: sectionIndex,
      progress_percentage: percentage,
      section_data: { correct, total, percentage }
    })
  } catch (error) {
    console.error('Failed to save progress:', error)
  }
}

// Retry vocabulary section
const retryVocabSection = (sectionIndex, section) => {
  delete sectionResults.value[sectionIndex]
  const questions = getVocabQuestions(section, sectionIndex)
  questions.forEach((_, qIndex) => {
    delete answeredQuestions.value[`${sectionIndex}-${qIndex}`]
    delete userAnswers.value[`${sectionIndex}-${qIndex}`]
  })
  currentVocabQuestion.value[sectionIndex] = 0
  delete shuffledVocabQuestions.value[sectionIndex]
  delete generatedVocabQuestions.value[`vocab-${sectionIndex}`]
}

// Helper to get correct answer index
const getCorrectAnswerIndex = (sectionIndex, qIndex, questions) => {
  const question = questions[qIndex]
  if (!question) return -1
  
  // Try different field names for the answer
  const ans = question.answer !== undefined ? question.answer : 
             (question.correctAnswer !== undefined ? question.correctAnswer : 
             (question.correct_answer !== undefined ? question.correct_answer : null))
  
  if (ans === null) return -1
  
  // If it's already a number (index)
  if (typeof ans === 'number') return ans
  
  // If it's a string, find the matching option index
  if (typeof ans === 'string' && question.options) {
    return question.options.findIndex(opt => {
      const optText = typeof opt === 'object' ? (opt.text || opt.option || '') : opt
      return optText === ans
    })
  }
  return -1
}

// Status helpers for quiz UI
const isOptionSelected = (sectionIndex, qIndex, oIndex) => {
  return userAnswers.value[`${sectionIndex}-${qIndex}`] === oIndex
}

const isOptionCorrect = (sectionIndex, qIndex, oIndex, questions) => {
  return getCorrectAnswerIndex(sectionIndex, qIndex, questions) === oIndex
}

const handleAnswerSelect = (sectionIndex, qIndex) => {
  // Mark as answered immediately
  answeredQuestions.value[`${sectionIndex}-${qIndex}`] = true
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

const courseId = parseInt(route.params.id)

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
    const groupKey = section.difficulty || section.level || 'Other Sections'
    
    if (!grouped[groupKey]) {
      grouped[groupKey] = {
        sections: [],
        startIndex: currentIndex
      }
      if (expandedGroups.value[groupKey] === undefined) {
        expandedGroups.value[groupKey] = Object.keys(grouped).length === 1
      }
    }
    
    grouped[groupKey].sections.push(section)
    currentIndex++
  })
  
  const levelOrder = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2']
  const sortedKeys = Object.keys(grouped).sort((a, b) => {
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
  loading.value = true
  try {
    const response = await axios.get(`/api/admin/courses/${courseId}`)

    if (response.data.success && response.data.data) {
      const courseData = response.data.data
      
      course.value = {
        id: courseData.id,
        course_title: courseData.course_title || courseData.title || 'Untitled Course',
        course_subtitle: courseData.course_subtitle || '',
        course_description: courseData.course_description || courseData.description || '',
        course_category: courseData.course_category || '',
        course_language: courseData.course_language || '',
        course_level: courseData.course_level || '',
        course_total_texts: courseData.course_total_texts || 0,
        course_banner: courseData.course_banner || null
      }

      // Parse course content from JSON
      try {
        let jsonContent = courseData.course_json_content

        if (typeof jsonContent === 'string') {
          jsonContent = JSON.parse(jsonContent)
        }

        if (Array.isArray(jsonContent)) {
          const processedSections = []
          
          jsonContent.forEach((section, sectionIndex) => {
            if (section.activities && Array.isArray(section.activities)) {
              section.activities.forEach((activity, activityIndex) => {
                processedSections.push({
                  ...activity,
                  sectionIndex: sectionIndex,
                  activityIndex: activityIndex,
                  category: section.category,
                  difficulty: section.difficulty,
                  sectionTitle: activity.title || `Section ${processedSections.length + 1}`,
                  questions: activity.questions || []
                })
              })
            } else {
              processedSections.push({
                ...section,
                sectionIndex: sectionIndex,
                sectionTitle: section.section || section.title || `Section ${processedSections.length + 1}`,
                questions: section.questions || []
              })
            }
          })
          
          courseSections.value = processedSections
        } else if (jsonContent && jsonContent.activities && Array.isArray(jsonContent.activities)) {
          courseSections.value = jsonContent.activities.map((activity, index) => ({
            ...activity,
            sectionTitle: activity.title || `Section ${index + 1}`,
            questions: activity.questions || []
          }))
        } else if (jsonContent) {
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

      // Initialize expanded sections
      courseSections.value.forEach((_, index) => {
        expandedSections.value[index] = false
      })
      
      await nextTick()
      const firstGroupKey = Object.keys(groupedSections.value)[0]
      if (firstGroupKey) {
        expandedGroups.value[firstGroupKey] = true
        if (groupedSections.value[firstGroupKey].sections.length > 0) {
          expandedSections.value[groupedSections.value[firstGroupKey].startIndex] = true
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

// Go back functionality
const goBack = () => {
  router.push('/tutor/courses')
}

// Toggle section expansion
const toggleSection = (index) => {
  expandedSections.value[index] = !expandedSections.value[index]
}

// Toggle group expansion
const toggleGroup = (groupKey) => {
  expandedGroups.value[groupKey] = !expandedGroups.value[groupKey]
}

// Scroll to section
const scrollToSection = (index) => {
  expandedSections.value[index] = true
  // Find the group containing this section
  for (const [groupKey, group] of Object.entries(groupedSections.value)) {
    if (index >= group.startIndex && index < group.startIndex + group.sections.length) {
      expandedGroups.value[groupKey] = true
      break
    }
  }
  setTimeout(() => {
    const element = document.querySelector(`[data-section-index="${index}"]`)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }, 100)
}

// Clear answers for a section
const clearAnswers = (index, questions) => {
  questions.forEach((_, qIndex) => {
    delete userAnswers.value[`${index}-${qIndex}`]
    delete answeredQuestions.value[`${index}-${qIndex}`]
  })
}

// Finish section and calculate score
const finishSection = async (index, questions) => {
  let correct = 0

  questions.forEach((question, qIndex) => {
    const userAnswer = userAnswers.value[`${index}-${qIndex}`]
    const correctIndex = getCorrectAnswerIndex(index, qIndex, questions)

    if (userAnswer === correctIndex && correctIndex !== -1) {
      correct++
    }
  })

  const total = questions.length
  const percentage = total > 0 ? Math.round((correct / total) * 100) : 0

  sectionResults.value[index] = {
    correct,
    total,
    percentage
  }

  // Save progress to database
  try {
    await axios.post(`/api/tutor/progress/${courseId}/section`, {
      activity_id: index,
      progress_percentage: percentage,
      section_data: {
        correct,
        total,
        percentage,
        user_answers: Object.fromEntries(
          Object.entries(userAnswers.value).filter(([key]) => key.startsWith(`${index}-`))
        )
      }
    })
  } catch (error) {
    console.error('Failed to save progress:', error)
  }
}

// Retry section
const retrySection = (index, questions) => {
  delete sectionResults.value[index]
  questions.forEach((_, qIndex) => {
    delete answeredQuestions.value[`${index}-${qIndex}`]
  })
  clearAnswers(index, questions)
}

// Next section
const nextSection = (index) => {
  if (index < courseSections.value.length - 1) {
    // Close current section
    expandedSections.value[index] = false
    
    // Open next section
    expandedSections.value[index + 1] = true
    
    // Find the group containing the next section and expand it
    const nextSec = courseSections.value[index + 1]
    const groupKey = nextSec.difficulty || nextSec.level || 'Other Sections'
    expandedGroups.value[groupKey] = true

    // Scroll to the next lesson with a slight offset
    nextTick(() => {
      const el = document.querySelector(`[data-section-index="${index + 1}"]`)
      if (el) {
        const offset = 100
        const elementPosition = el.getBoundingClientRect().top + window.pageYOffset
        const offsetPosition = elementPosition - offset

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        })
      }
    })
  }
}

// Reset all answers in a group
const resetGroupAnswers = (groupKey) => {
  resetTargetGroup.value = groupKey
  showResetModal.value = true
}

const confirmReset = async () => {
  const groupKey = resetTargetGroup.value
  const group = groupedSections.value[groupKey]
  if (group) {
    const activityIds = []
    group.sections.forEach((_, sIndex) => {
      const globalIndex = group.startIndex + sIndex
      activityIds.push(globalIndex)
      
      delete sectionResults.value[globalIndex]
      
      const questions = courseSections.value[globalIndex].questions || []
      questions.forEach((_, qIndex) => {
        delete answeredQuestions.value[`${globalIndex}-${qIndex}`]
        delete userAnswers.value[`${globalIndex}-${qIndex}`]
      })
    })

    // API call to reset in database
    try {
      await axios.post(`/api/tutor/progress/${courseId}/reset`, {
        activity_ids: activityIds
      })
    } catch (error) {
      console.error('Failed to reset progress in database:', error)
    }
  }
  showResetModal.value = false
  resetTargetGroup.value = ''
}

// Load saved progress from database
const loadSavedProgress = async () => {
  try {
    const response = await axios.get(`/api/tutor/progress/${courseId}`)
    if (response.data.success && response.data.data) {
      const progressDetails = response.data.data

      // Restore section results and user answers from saved progress
      if (Array.isArray(progressDetails)) {
        progressDetails.forEach(progress => {
          if (progress.activity_type === 'section') {
            const sectionIndex = progress.activity_id

            // Restore section results
            if (progress.section_data) {
              const sectionData = typeof progress.section_data === 'string'
                ? JSON.parse(progress.section_data)
                : progress.section_data

              sectionResults.value[sectionIndex] = {
                correct: sectionData.correct || 0,
                total: sectionData.total || 0,
                percentage: sectionData.percentage || progress.progress_percentage || 0
              }

              // Restore user answers
              if (sectionData.user_answers) {
                Object.entries(sectionData.user_answers).forEach(([key, value]) => {
                  userAnswers.value[key] = value
                })
              }
            } else if (progress.progress_percentage === 100) {
              // Legacy: if section was completed but no section_data
              sectionResults.value[sectionIndex] = {
                correct: 0,
                total: 0,
                percentage: progress.progress_percentage
              }
            }
          }
        })
      }
    }
  } catch (error) {
    console.error('Failed to load saved progress:', error)
  }
}

onMounted(async () => {
  await fetchCourse()
  await loadSavedProgress()
})
</script>

<style scoped>
/* Smooth transitions */
* {
  transition: background-color 0.2s ease, border-color 0.2s ease;
}
</style>
