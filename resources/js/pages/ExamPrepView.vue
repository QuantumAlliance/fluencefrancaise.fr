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
              <router-link to="/student/exam-prep" class="text-gray-700 hover:text-[#0055A4] transition">My Exam Prep</router-link>
              <router-link to="/student/dashboard" class="text-gray-700 hover:text-[#0055A4] transition">Dashboard</router-link>
            </template>
            <template v-else-if="auth.user?.user_type === 'tutor'">
              <router-link to="/tutor/exam-prep" class="text-gray-700 hover:text-[#0055A4] transition">Exam Prep</router-link>
              <router-link to="/tutor/dashboard" class="text-gray-700 hover:text-[#0055A4] transition">Dashboard</router-link>
            </template>
            <template v-else-if="auth.user?.user_type === 'admin' || auth.user?.user_type === 'superadmin'">
              <router-link to="/admin/exam-prep" class="text-gray-700 hover:text-[#0055A4] transition">Exam Prep</router-link>
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
    <div v-if="loading" class="flex items-center justify-center h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
    </div>

    <div v-else-if="examPrep">
      <!-- Header Banner -->
      <div class="relative h-44 md:h-52 overflow-hidden bg-gradient-to-br from-[#0055A4] via-[#003d7a] to-[#002654]">
        <img
          v-if="examPrep.exam_prep_banner"
          :src="getBannerImageUrl(examPrep.exam_prep_banner)"
          :alt="examPrep.exam_prep_title"
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
          <p v-if="examPrep.exam_prep_category" class="inline-block text-xs font-semibold tracking-wider uppercase text-white/90 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full mb-2">
            {{ examPrep.exam_prep_category }}
          </p>
          <h1 class="text-2xl md:text-4xl font-bold text-white drop-shadow-sm">{{ examPrep.exam_prep_title }}</h1>
          <p v-if="examPrep.exam_prep_subtitle" class="text-sm md:text-base text-white/90 mt-1 line-clamp-1">{{ examPrep.exam_prep_subtitle }}</p>
        </div>
      </div>

      <div v-if="isLocked" class="max-w-3xl mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
          <div class="mx-auto w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800 mb-3">This Exam Prep is Locked</h2>
          <p class="text-gray-600 mb-6">
            You don't have access to <strong>{{ examPrep.exam_prep_title }}</strong> yet. Please ask your tutor to grant you access.
          </p>
          <button
            @click="goBack"
            class="px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
          >
            Back to Exam Prep
          </button>
        </div>
      </div>

      <div v-else class="max-w-6xl mx-auto">
        <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-32 md:pb-48">
          <!-- ============================================================ -->
          <!-- WRITTEN EXPRESSION: combinaisons + tasks layout              -->
          <!-- Used when the JSON has { month, combinaisons: [{ tasks }] }  -->
          <!-- ============================================================ -->
          <template v-if="isWrittenExpressionFormat">
            <!-- Empty state: category is Written Expression but no combinaisons yet -->
            <div v-if="!hasCombinaisons" class="bg-white rounded-2xl border border-gray-200 p-8 md:p-12 text-center">
              <div class="mx-auto w-16 h-16 rounded-full bg-[#0055A4]/10 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#0055A4]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">No combinaisons yet</h3>
              <p class="text-sm text-gray-500 max-w-md mx-auto">
                This is a <strong>Written Expression</strong> exam prep. Add a JSON section with a <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">combinaisons</code> array (each with <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">tasks[]</code>) to populate it.
              </p>
            </div>

            <!-- Section (month) selector — only shown when JSON has multiple sections -->
            <div v-else-if="showWrittenSectionSelector">
              <div class="text-center mb-8">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Choose a Section</h3>
                <p class="text-sm md:text-base text-gray-500 mt-2">
                  {{ writtenSections.length }} sections available · written expression
                </p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <button
                  v-for="(section, sIdx) in writtenSections"
                  :key="sIdx"
                  @click="goToWrittenSection(sIdx)"
                  class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 hover:border-[#0055A4]/50 hover:shadow-xl hover:-translate-y-1 p-6 text-left transition-all duration-300"
                >
                  <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-gradient-to-br from-[#0055A4]/10 to-[#003d7a]/10 group-hover:from-[#0055A4]/20 group-hover:to-[#003d7a]/20 transition-all"></div>

                  <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0055A4] to-[#003d7a] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-[#0055A4]/30">
                      {{ sIdx + 1 }}
                    </div>
                  </div>

                  <h4 class="relative text-lg md:text-xl font-bold text-gray-900 leading-snug mb-3 group-hover:text-[#003d7a] transition-colors">
                    {{ section.month || `Section ${sIdx + 1}` }}
                  </h4>

                  <div class="relative flex items-center gap-3 text-xs text-gray-500 mb-4">
                    <span class="inline-flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                      <span class="font-medium tabular-nums">{{ section.combinaisons.length }}</span> combinaisons
                    </span>
                  </div>

                  <div class="relative flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <span class="text-sm font-semibold text-[#003d7a] group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1">
                      Open section
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                      </svg>
                    </span>
                  </div>
                </button>
              </div>
            </div>

            <!-- Combinaison selector grid (scoped to the current section) -->
            <div v-else-if="selectedCombinaisonIndex === null">
              <div v-if="writtenSections.length > 1" class="mb-6">
                <button
                  @click="backToWrittenSections"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-sm font-medium text-gray-700 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                  </svg>
                  Back to sections
                </button>
              </div>

              <div class="text-center mb-8">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Choose a Combinaison</h3>
                <p class="text-sm md:text-base text-gray-500 mt-2">
                  <span v-if="currentWrittenSection?.month" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0055A4]/10 text-[#003d7a] text-xs font-semibold mr-2">
                    {{ currentWrittenSection.month }}
                  </span>
                  {{ currentCombinaisons.length }} {{ currentCombinaisons.length === 1 ? 'combinaison' : 'combinaisons' }} available
                </p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <button
                  v-for="(c, cIdx) in currentCombinaisons"
                  :key="cIdx"
                  @click="goToCombinaison(cIdx)"
                  class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 hover:border-[#0055A4]/50 hover:shadow-xl hover:-translate-y-1 p-6 text-left transition-all duration-300"
                >
                  <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-gradient-to-br from-[#0055A4]/10 to-[#003d7a]/10 group-hover:from-[#0055A4]/20 group-hover:to-[#003d7a]/20 transition-all"></div>

                  <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0055A4] to-[#003d7a] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-[#0055A4]/30">
                      {{ c.number || (cIdx + 1) }}
                    </div>
                    <span
                      v-if="isCombinaisonDone(effectiveSectionIndex, cIdx)"
                      class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-1 rounded-full"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                      Done
                    </span>
                  </div>

                  <h4 class="relative text-lg md:text-xl font-bold text-gray-900 leading-snug mb-3 group-hover:text-[#003d7a] transition-colors">
                    {{ c.title || `Combinaison ${c.number || cIdx + 1}` }}
                  </h4>

                  <div class="relative flex items-center gap-3 text-xs text-gray-500 mb-4">
                    <span class="inline-flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                      <span class="font-medium tabular-nums">{{ (c.tasks || []).length }}</span> tâches
                    </span>
                  </div>

                  <div class="relative flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <span
                      class="text-sm font-semibold group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1"
                      :class="isCombinaisonDone(effectiveSectionIndex, cIdx) ? 'text-green-700' : 'text-[#003d7a]'"
                    >
                      {{ isCombinaisonDone(effectiveSectionIndex, cIdx) ? 'Review' : 'Open combinaison' }}
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                      </svg>
                    </span>
                  </div>
                </button>
              </div>
            </div>

            <!-- Combinaison detail: all tâches + corrections inline -->
            <div v-else>
              <div class="flex items-center gap-4 mb-6">
                <button
                  @click="backToCombinaisons"
                  class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-sm font-medium text-gray-700 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                  </svg>
                  Back to combinaisons
                </button>
              </div>

              <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 whitespace-nowrap">
                  {{ currentCombinaison?.title || `Combinaison ${currentCombinaison?.number || (selectedCombinaisonIndex + 1)}` }}
                </h2>
                <div class="flex-1 h-px bg-gradient-to-r from-[#0055A4]/40 to-transparent"></div>
                <button
                  @click="toggleCombinaisonDone"
                  class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all"
                  :class="isCurrentCombinaisonDone
                    ? 'bg-green-50 text-green-700 border-2 border-green-200 hover:bg-green-100'
                    : 'bg-white text-[#003d7a] border-2 border-[#0055A4] hover:bg-[#0055A4] hover:text-white'"
                >
                  <svg v-if="isCurrentCombinaisonDone" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ isCurrentCombinaisonDone ? 'Done' : 'Mark as done' }}
                </button>
              </div>

              <div class="space-y-8">
                <div
                  v-for="(task, tIdx) in (currentCombinaison?.tasks || [])"
                  :key="tIdx"
                >
                  <!-- Task badge -->
                  <div class="inline-flex items-center px-4 py-1.5 rounded-xl border-2 border-[#0055A4] bg-white text-[#003d7a] font-bold text-sm md:text-base mb-3 shadow-sm">
                    {{ task.title || `Tâche ${task.number || tIdx + 1}` }}
                  </div>

                  <!-- Task content box -->
                  <div class="bg-white rounded-2xl border-2 border-[#0055A4]/50 p-5 md:p-6 shadow-sm">
                    <p v-if="task.subject" class="text-base md:text-lg font-bold text-gray-900 mb-4">
                      {{ task.subject }}
                    </p>
                    <p v-if="task.prompt" class="text-gray-800 leading-relaxed whitespace-pre-line">
                      {{ task.prompt }}
                    </p>
                    <div v-if="task.document_1" class="mt-4 p-4 bg-[#0055A4]/5 rounded-lg border border-[#0055A4]/15">
                      <p class="text-sm font-semibold text-[#003d7a] mb-2">{{ task.document_1.title }}</p>
                      <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ task.document_1.text }}</p>
                    </div>
                    <div v-if="task.document_2" class="mt-3 p-4 bg-[#0055A4]/5 rounded-lg border border-[#0055A4]/15">
                      <p class="text-sm font-semibold text-[#003d7a] mb-2">{{ task.document_2.title }}</p>
                      <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ task.document_2.text }}</p>
                    </div>
                    <p v-if="task.word_limit" class="text-right text-sm font-bold text-[#003d7a] mt-4">
                      {{ task.word_limit }}
                    </p>
                  </div>

                  <!-- Correction badge -->
                  <div class="inline-flex items-center px-4 py-1.5 rounded-xl border-2 border-[#8c5a2a] bg-white text-[#5e3d1c] font-bold text-sm md:text-base mt-5 mb-3 shadow-sm">
                    {{ task.correction_title || `Correction ${task.title || 'Tâche ' + (task.number || tIdx + 1)}` }}
                  </div>

                  <!-- Correction content box -->
                  <div
                    class="relative bg-white rounded-2xl border-2 border-[#8c5a2a]/40 p-5 md:p-6 shadow-sm overflow-hidden"
                    :class="{ 'cursor-pointer group/correction': !isCorrectionRevealed(tIdx) }"
                    @click="!isCorrectionRevealed(tIdx) && revealCorrection(tIdx)"
                  >
                    <p
                      class="text-gray-800 leading-relaxed whitespace-pre-line transition-all duration-300 select-none"
                      :class="!isCorrectionRevealed(tIdx) ? 'blur-md pointer-events-none' : ''"
                    >{{ task.correction }}</p>

                    <!-- Click-to-reveal overlay -->
                    <div
                      v-if="!isCorrectionRevealed(tIdx)"
                      class="absolute inset-0 flex items-center justify-center bg-white/30 backdrop-blur-[1px] group-hover/correction:bg-white/40 transition-colors"
                    >
                      <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#0055A4] to-[#003d7a] text-white font-semibold text-sm shadow-lg shadow-[#0055A4]/30 group-hover/correction:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Click to reveal correction
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>

          <!-- ============================================================ -->
          <!-- ORAL/WRITTEN EXPRESSION: parties + scenarios layout         -->
          <!-- Used when the JSON has { parties: [{ partie, scenarios }] } -->
          <!-- ============================================================ -->
          <template v-else-if="isOralExpressionFormat">
            <!-- Empty state: category is Expression but JSON has neither parties nor essais yet -->
            <div v-if="!hasOralParties && !hasOralEssais" class="bg-white rounded-2xl border border-gray-200 p-8 md:p-12 text-center">
              <div class="mx-auto w-16 h-16 rounded-full bg-[#0055A4]/10 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#0055A4]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
              </div>
              <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">No content yet</h3>
              <p class="text-sm text-gray-500 max-w-md mx-auto">
                This is an <strong>Expression</strong> category exam prep. Add a JSON with a <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">parties</code> array (role-play scenarios) or an <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">essais</code> array (essay topics with corrections) to populate it.
              </p>
            </div>

            <!-- ESSAIS layout: list of essay topics with expandable corrections -->
            <div v-else-if="!hasOralParties && hasOralEssais">
              <div class="text-center mb-8">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Sujets d'expression</h3>
                <p class="text-sm md:text-base text-gray-500 mt-2">
                  <span v-if="essaisCategorie" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0055A4]/10 text-[#003d7a] text-xs font-semibold mr-2">
                    {{ essaisCategorie }}
                  </span>
                  {{ oralEssais.length }} {{ oralEssais.length === 1 ? 'sujet' : 'sujets' }} · cliquez pour voir la correction
                </p>
              </div>

              <div class="space-y-3">
                <div
                  v-for="(essai, eIdx) in oralEssais"
                  :key="eIdx"
                  class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm transition-all"
                  :class="expandedEssaiKey === `e${eIdx}` ? 'shadow-md' : ''"
                >
                  <button
                    @click="toggleOralEssai(eIdx)"
                    class="w-full flex items-start justify-between gap-4 px-5 py-4 text-left transition-colors"
                    :class="expandedEssaiKey === `e${eIdx}`
                      ? 'bg-gradient-to-r from-[#0055A4] to-[#003d7a] text-white'
                      : 'bg-gray-50 hover:bg-gradient-to-r hover:from-[#0055A4] hover:to-[#003d7a] hover:text-white text-gray-800'"
                  >
                    <div class="flex items-start gap-3 min-w-0">
                      <span
                        class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors"
                        :class="expandedEssaiKey === `e${eIdx}`
                          ? 'bg-white/20 text-white'
                          : 'bg-[#0055A4]/15 text-[#003d7a]'"
                      >
                        {{ eIdx + 1 }}
                      </span>
                      <p class="text-sm md:text-base font-semibold leading-relaxed flex-1 min-w-0">
                        {{ essai.sujet }}
                      </p>
                    </div>
                    <svg
                      class="w-5 h-5 flex-shrink-0 mt-1 transition-transform"
                      :class="expandedEssaiKey === `e${eIdx}` ? 'rotate-180' : ''"
                      fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </button>

                  <div v-show="expandedEssaiKey === `e${eIdx}`" class="px-5 py-5 md:px-7 md:py-6 bg-white border-t border-[#0055A4]/20">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#003d7a] mb-3">
                      Correction modèle
                    </p>
                    <div class="prose prose-sm md:prose-base max-w-none text-gray-800 leading-relaxed whitespace-pre-line">{{ essai.correction }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Partie selector grid -->
            <div v-else-if="selectedPartieIndex === null">
              <div class="relative mb-8">
                <div class="text-center">
                  <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Choose a Partie</h3>
                  <p class="text-sm md:text-base text-gray-500 mt-2">
                    {{ oralParties.length }} {{ oralParties.length === 1 ? 'partie' : 'parties' }} available · role-play scenarios
                  </p>
                </div>
                <!-- "?" help icon — only shown when a description is available -->
                <button
                  v-if="examPrep.exam_prep_description"
                  @click="descriptionModalOpen = true"
                  type="button"
                  aria-label="About this exam prep"
                  title="About this exam prep"
                  class="absolute right-0 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-14 h-14 md:w-16 md:h-16 rounded-full bg-white border-2 border-[#0055A4] text-[#003d7a] hover:bg-[#0055A4] hover:text-white shadow-md hover:shadow-lg transition-all"
                >
                  <svg class="w-8 h-8 md:w-9 md:h-9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093M12 17h.01"/>
                    <circle cx="12" cy="12" r="9"/>
                  </svg>
                </button>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <button
                  v-for="(partie, pIdx) in oralParties"
                  :key="pIdx"
                  @click="goToPartie(pIdx)"
                  class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 hover:border-[#0055A4]/50 hover:shadow-xl hover:-translate-y-1 p-6 text-left transition-all duration-300"
                >
                  <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-gradient-to-br from-[#0055A4]/10 to-[#003d7a]/10 group-hover:from-[#0055A4]/20 group-hover:to-[#003d7a]/20 transition-all"></div>

                  <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0055A4] to-[#003d7a] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-[#0055A4]/30">
                      {{ pIdx + 1 }}
                    </div>
                    <span
                      v-if="isPartieDone(pIdx)"
                      class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-1 rounded-full"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                      Done
                    </span>
                  </div>

                  <h4 class="relative text-lg md:text-xl font-bold text-gray-900 leading-snug mb-3 group-hover:text-[#003d7a] transition-colors">
                    {{ partie.partie || `Partie ${pIdx + 1}` }}
                  </h4>

                  <div class="relative flex items-center gap-3 text-xs text-gray-500 mb-4">
                    <span class="inline-flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                      </svg>
                      <span class="font-medium tabular-nums">{{ (partie.scenarios || []).length }}</span> scenarios
                    </span>
                  </div>

                  <div class="relative flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                    <span
                      class="text-sm font-semibold group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1"
                      :class="isPartieDone(pIdx) ? 'text-green-700' : 'text-[#003d7a]'"
                    >
                      {{ isPartieDone(pIdx) ? 'Review' : 'Open partie' }}
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                      </svg>
                    </span>
                  </div>
                </button>
              </div>
            </div>

            <!-- Scenarios view for the selected partie -->
            <div v-else>
              <div class="mb-6">
                <button
                  @click="backToParties"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-sm font-medium text-gray-700 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                  </svg>
                  Back to parties
                </button>
              </div>

              <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 whitespace-nowrap">
                  {{ oralParties[selectedPartieIndex]?.partie || `Partie ${selectedPartieIndex + 1}` }}
                </h2>
                <div class="flex-1 h-px bg-gradient-to-r from-[#0055A4]/40 to-transparent"></div>
                <button
                  @click="togglePartieDone"
                  class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all"
                  :class="isCurrentPartieDone
                    ? 'bg-green-50 text-green-700 border-2 border-green-200 hover:bg-green-100'
                    : 'bg-white text-[#003d7a] border-2 border-[#0055A4] hover:bg-[#0055A4] hover:text-white'"
                >
                  <svg v-if="isCurrentPartieDone" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ isCurrentPartieDone ? 'Done' : 'Mark as done' }}
                </button>
              </div>

              <div class="space-y-3">
                <div
                  v-for="(scenario, sIdx) in (oralParties[selectedPartieIndex]?.scenarios || [])"
                  :key="sIdx"
                  class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm transition-all"
                  :class="expandedScenarioKey === `p${selectedPartieIndex}-s${sIdx}` ? 'shadow-md' : ''"
                >
                  <button
                    @click="togglePartieScenario(selectedPartieIndex, sIdx)"
                    class="w-full flex items-start justify-between gap-4 px-5 py-4 text-left transition-colors"
                    :class="expandedScenarioKey === `p${selectedPartieIndex}-s${sIdx}`
                      ? 'bg-gradient-to-r from-[#0055A4] to-[#003d7a] text-white'
                      : 'bg-gray-50 hover:bg-gradient-to-r hover:from-[#0055A4] hover:to-[#003d7a] hover:text-white text-gray-800'"
                  >
                    <div class="flex items-start gap-3 min-w-0">
                      <span
                        class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors"
                        :class="expandedScenarioKey === `p${selectedPartieIndex}-s${sIdx}`
                          ? 'bg-white/20 text-white'
                          : 'bg-[#0055A4]/15 text-[#003d7a] group-hover:bg-white/20 group-hover:text-white'"
                      >
                        {{ sIdx + 1 }}
                      </span>
                      <p class="text-sm md:text-base font-semibold leading-relaxed flex-1 min-w-0">
                        {{ scenario.title }}
                      </p>
                    </div>
                    <svg
                      class="w-5 h-5 flex-shrink-0 mt-1 transition-transform"
                      :class="expandedScenarioKey === `p${selectedPartieIndex}-s${sIdx}` ? 'rotate-180' : ''"
                      fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </button>

                  <div v-show="expandedScenarioKey === `p${selectedPartieIndex}-s${sIdx}`" class="px-5 py-5 md:px-7 md:py-6 bg-white border-t border-[#0055A4]/20">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#003d7a] mb-3">
                      Questions à poser
                    </p>
                    <div
                      class="relative overflow-hidden"
                      :class="{ 'cursor-pointer group/qreveal': !isScenarioQuestionsRevealed(sIdx) }"
                      @click="!isScenarioQuestionsRevealed(sIdx) && revealScenarioQuestions(sIdx)"
                    >
                      <ol
                        class="space-y-2.5 text-gray-800 text-base leading-relaxed transition-all duration-300 select-none"
                        :class="!isScenarioQuestionsRevealed(sIdx) ? 'blur-md pointer-events-none' : ''"
                      >
                        <li
                          v-for="(q, qIdx) in (scenario.questions || [])"
                          :key="qIdx"
                          class="flex gap-3"
                        >
                          <span class="text-[#003d7a] font-semibold flex-shrink-0 min-w-[1.5rem]">{{ qIdx + 1 }}.</span>
                          <span>{{ q.replace(/^\d+\.\s*/, '') }}</span>
                        </li>
                      </ol>

                      <!-- Click-to-reveal overlay -->
                      <div
                        v-if="!isScenarioQuestionsRevealed(sIdx)"
                        class="absolute inset-0 flex items-center justify-center bg-white/30 backdrop-blur-[1px] group-hover/qreveal:bg-white/40 transition-colors"
                      >
                        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#0055A4] to-[#003d7a] text-white font-semibold text-sm shadow-lg shadow-[#0055A4]/30 group-hover/qreveal:scale-105 transition-transform">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                          </svg>
                          Click to reveal questions
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Description popup modal — opened by the "?" icon on the partie selector -->
            <div
              v-if="descriptionModalOpen"
              class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
              @click.self="descriptionModalOpen = false"
            >
              <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
                <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-[#0055A4]/5 to-[#003d7a]/5">
                  <div class="flex items-center gap-3 min-w-0">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#0055A4]/10 text-[#003d7a] flex-shrink-0">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </span>
                    <h3 class="text-base md:text-lg font-bold text-gray-900 truncate">{{ examPrep.exam_prep_description_title || 'About this exam prep' }}</h3>
                  </div>
                  <button
                    @click="descriptionModalOpen = false"
                    type="button"
                    aria-label="Close"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </button>
                </div>
                <div class="px-6 py-5 overflow-y-auto">
                  <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ examPrep.exam_prep_description }}</p>
                </div>
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end">
                  <button
                    @click="descriptionModalOpen = false"
                    type="button"
                    class="px-4 py-2 rounded-lg bg-[#0055A4] hover:bg-[#003d7a] text-white text-sm font-semibold transition-colors"
                  >
                    Got it
                  </button>
                </div>
              </div>
            </div>
          </template>

          <!-- Test selector (shown when no test is picked yet) -->
          <div v-else-if="!selectedGroupKey">
            <div class="text-center mb-8">
              <h3 class="text-2xl md:text-3xl font-bold text-gray-900">Choose a test</h3>
              <p class="text-sm md:text-base text-gray-500 mt-2">
                {{ Object.keys(groupedSections).length }} {{ Object.keys(groupedSections).length === 1 ? 'test' : 'tests' }} available
                <span class="mx-2 text-gray-300">·</span>
                60 minutes each
              </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
              <button
                v-for="(group, groupKey, gIdx) in groupedSections"
                :key="groupKey"
                @click="goToTest(groupKey)"
                class="group relative overflow-hidden bg-white rounded-2xl border border-gray-200 hover:border-[#0055A4]/50 hover:shadow-xl hover:-translate-y-1 p-6 text-left transition-all duration-300"
              >
                <!-- Decorative blob -->
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-gradient-to-br from-[#0055A4]/10 to-[#003d7a]/10 group-hover:from-[#0055A4]/20 group-hover:to-[#003d7a]/20 transition-all"></div>

                <!-- Number badge + status -->
                <div class="relative flex items-center justify-between mb-4">
                  <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0055A4] to-[#003d7a] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-[#0055A4]/30">
                    {{ gIdx + 1 }}
                  </div>
                  <span
                    v-if="groupAnsweredCount(group) === group.sections.length && group.sections.length > 0"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-1 rounded-full"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Done
                  </span>
                  <span
                    v-else-if="groupAnsweredCount(group) > 0"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-[#003d7a] bg-[#0055A4]/10 border border-[#0055A4]/20 px-2.5 py-1 rounded-full"
                  >
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0055A4] animate-pulse"></span>
                    In progress
                  </span>
                  <span
                    v-else
                    class="text-xs font-medium text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full"
                  >
                    Not started
                  </span>
                </div>

                <!-- Title -->
                <h4 class="relative text-base md:text-lg font-bold text-gray-900 leading-snug line-clamp-2 mb-3 min-h-[3rem] group-hover:text-[#003d7a] transition-colors">
                  {{ groupKey || 'Other Sections' }}
                </h4>

                <!-- Stats row -->
                <div class="relative flex items-center gap-3 text-xs text-gray-500 mb-4">
                  <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="font-medium tabular-nums">{{ group.sections.length }}</span> questions
                  </span>
                  <span class="text-gray-200">·</span>
                  <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    60 min
                  </span>
                </div>

                <!-- Progress bar -->
                <div class="relative">
                  <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-medium text-gray-600">Progress</span>
                    <span class="font-bold tabular-nums text-gray-900">
                      {{ groupAnsweredCount(group) }}<span class="text-gray-400">/{{ group.sections.length }}</span>
                    </span>
                  </div>
                  <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div
                      :style="{ width: groupProgressPercent(group) + '%' }"
                      class="h-full bg-gradient-to-r from-[#0055A4] to-[#003d7a] rounded-full transition-all duration-500"
                    ></div>
                  </div>
                </div>

                <!-- Bottom CTA -->
                <div class="relative flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                  <span
                    class="text-sm font-semibold group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-1"
                    :class="groupAnsweredCount(group) === group.sections.length && group.sections.length > 0
                      ? 'text-green-700'
                      : 'text-[#003d7a]'"
                  >
                    {{
                      groupAnsweredCount(group) === group.sections.length && group.sections.length > 0
                        ? 'View result'
                        : (groupAnsweredCount(group) > 0 ? 'Continue' : 'Start test')
                    }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                  </span>
                </div>
              </button>
            </div>
          </div>

          <!-- Quiz view (shown after a test is picked) -->
          <div
            v-else-if="!isOralExpressionFormat"
            :style="isOraleCategory ? { zoom: quizZoom } : {}"
          >
          <!-- Back button + test title in one row -->
          <div class="flex items-center gap-4 mb-5">
            <button
              @click="goToTestList"
              class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-sm font-medium text-gray-700 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              Back to tests
            </button>
            <h3 class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ selectedGroupKey }}</h3>
          </div>

          <!-- TCF Results panel (shown after Finish; replaces the quiz UI for this test) -->
          <div v-if="showResults && currentGroupResult" class="space-y-6">
            <!-- Time taken pill (only shown when we actually measured it) -->
            <div v-if="timeTakenSeconds > 0" class="flex items-center justify-center">
              <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#0055A4]/10 border border-[#0055A4]/30 text-[#003d7a] rounded-full text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Completed in {{ formatDurationShort(timeTakenSeconds) }}
              </div>
            </div>

            <!-- Score + TCF Level achievement (NCLC shown as a secondary label) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2 bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-200">
                <p class="text-xs font-semibold tracking-wider uppercase text-gray-500 text-center mb-2">Score</p>
                <p class="text-center text-4xl md:text-5xl font-bold text-gray-900 tabular-nums">
                  {{ currentGroupResult.totalEarned }}
                  <span class="text-gray-300 mx-1">/</span>
                  <span class="text-gray-500 font-semibold">{{ currentGroupResult.totalPossible }}</span>
                </p>
                <p class="text-center text-sm text-gray-500 mt-2">
                  ({{ currentGroupResult.percentage }}%) point(s) reached
                </p>
              </div>
              <div
                class="rounded-2xl p-5 flex flex-col items-center justify-center border-2 transition-all"
                :class="currentGroupResult.achieved
                  ? 'bg-gradient-to-br from-[#0055A4]/10 to-[#003d7a]/10 border-[#0055A4]'
                  : 'bg-gray-50 border-gray-200'"
              >
                <p class="text-5xl md:text-6xl font-bold leading-none"
                   :class="currentGroupResult.achieved ? 'text-[#003d7a]' : 'text-gray-400'">
                  {{ currentGroupResult.level }}
                </p>
                <p class="mt-2 text-xs font-bold tracking-widest uppercase"
                   :class="currentGroupResult.achieved ? 'text-[#0055A4]' : 'text-gray-400'">
                  {{ currentGroupResult.achieved ? 'ATTEINT' : 'NON ATTEINT' }}
                </p>
                <!-- NCLC equivalence — secondary label, only for orale/written -->
                <div
                  v-if="currentGroupResult.nclcInfo"
                  class="mt-3 pt-3 w-full text-center border-t border-[#0055A4]/30"
                >
                  <p class="text-2xl md:text-3xl font-bold leading-none tracking-tight text-[#003d7a]">
                    {{ currentGroupResult.nclcInfo.label }}
                  </p>
                  <p class="mt-1.5 text-[10px] font-bold tracking-widest uppercase text-[#0055A4]">
                    NCLC
                  </p>
                </div>
              </div>
            </div>

            <!-- TCF level progress bars (A1..C2). Current bar also shows the NCLC
                 equivalent as a pill below the range (only for orale/written). -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 md:p-6 shadow-sm">
              <div class="grid grid-cols-6 gap-2 md:gap-4">
                <div
                  v-for="lvl in currentGroupResult.levelBars"
                  :key="lvl.label"
                  class="flex flex-col items-center"
                >
                  <p class="text-xl md:text-2xl font-bold mb-1"
                     :class="lvl.state === 'locked' ? 'text-gray-300' : (lvl.state === 'current' ? 'text-gray-900' : 'text-[#003d7a]')">
                    {{ lvl.label }}
                  </p>
                  <p class="text-[10px] md:text-xs font-bold tracking-wider uppercase mb-2"
                     :class="lvl.state === 'current' ? 'text-[#0055A4]' : 'text-gray-400'">
                    {{ lvl.state === 'current' ? 'CURRENT' : (lvl.state === 'achieved' ? 'DONE' : 'GOAL') }}
                  </p>
                  <div class="relative w-full h-32 md:h-36 rounded-xl border-2 overflow-hidden bg-gray-50"
                       :class="lvl.state === 'current' ? 'border-[#0055A4]' : (lvl.state === 'achieved' ? 'border-[#003d7a]' : 'border-gray-200')">
                    <div
                      class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#003d7a] to-[#0055A4] transition-all duration-700"
                      :style="{ height: lvl.percent + '%' }"
                    >
                      <span
                        v-if="lvl.percent > 0 && lvl.percent < 100"
                        class="absolute top-2 left-1/2 -translate-x-1/2 text-white text-xs md:text-sm font-bold"
                      >{{ lvl.percent }}%</span>
                    </div>
                  </div>
                  <p class="text-[10px] md:text-xs text-gray-500 mt-2 tabular-nums">{{ lvl.range }}</p>
                  <span
                    v-if="lvl.state === 'current' && currentGroupResult.nclcInfo"
                    class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full bg-[#0055A4]/10 text-[#003d7a] text-[10px] md:text-xs font-bold whitespace-nowrap"
                  >
                    {{ currentGroupResult.nclcInfo.label }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Next goal banner -->
            <div
              v-if="currentGroupResult.nextGoal"
              class="bg-[#0055A4]/10 border border-[#0055A4]/30 rounded-xl py-3 px-5 text-center text-sm md:text-base text-gray-800"
            >
              <span class="text-[#003d7a] font-bold">→ {{ currentGroupResult.nextGoal.pointsNeeded }}</span>
              more point(s) to reach
              <span class="font-bold text-[#003d7a]">{{ currentGroupResult.nextGoal.label }}</span>
            </div>

            <!-- Question grid -->
            <div class="bg-white rounded-2xl border border-gray-100 p-4 md:p-6 shadow-sm">
              <div class="grid gap-2" style="grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));">
                <div
                  v-for="cell in currentGroupResult.cells"
                  :key="cell.qNum"
                  class="relative rounded-xl px-3 py-3 text-center font-semibold"
                  :class="[pointsTier(cell.points).bg, pointsTier(cell.points).text]"
                >
                  <p class="text-sm md:text-base font-bold leading-tight">Q{{ cell.qNum }}</p>
                  <p class="text-[10px] md:text-xs font-medium opacity-90">{{ cell.points }} PTS</p>
                  <span
                    v-if="cell.answered && cell.correct"
                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center ring-2 ring-white shadow"
                  >
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </span>
                  <span
                    v-else-if="cell.answered"
                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 flex items-center justify-center ring-2 ring-white shadow"
                  >
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                  </span>
                  <span
                    v-else
                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center ring-2 ring-white shadow"
                  >
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                  </span>
                </div>
              </div>
            </div>

            <!-- Result actions -->
            <div class="flex flex-col sm:flex-row gap-3">
              <button
                @click="enterReviewMode"
                class="flex-1 px-5 py-3 bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-gray-700 rounded-xl font-medium transition-colors"
              >
                Review Answers
              </button>
              <button
                @click="restartTest"
                class="flex-1 px-5 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-xl font-medium transition-colors"
              >
                Try Again
              </button>
              <button
                @click="goToTestList"
                class="flex-1 px-5 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-xl font-medium transition-colors"
              >
                Back to tests
              </button>
            </div>

            <!-- "How is this scored?" — collapsible scoring explanation -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
              <button
                @click="scoringInfoOpen = !scoringInfoOpen"
                class="w-full flex items-center justify-between gap-3 px-5 py-4 text-left hover:bg-gray-50 transition-colors"
              >
                <span class="flex items-center gap-3">
                  <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#0055A4]/10 text-[#003d7a]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </span>
                  <span class="text-base md:text-lg font-bold text-gray-900">How is this scored?</span>
                </span>
                <svg
                  class="w-5 h-5 text-gray-500 flex-shrink-0 transition-transform"
                  :class="scoringInfoOpen ? 'rotate-180' : ''"
                  fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <div v-show="scoringInfoOpen" class="px-5 pb-5 md:px-6 md:pb-6 border-t border-gray-100">
                <!-- Brief overview -->
                <p class="text-sm md:text-base text-gray-700 leading-relaxed mt-4">
                  Each test is scored on a <strong class="text-gray-900">0–699</strong> scale, matching the official TCF Canada format. Questions carry different points based on their difficulty, and your total score is then mapped to a CEFR level (A1–C2) — and, for <em>Compréhension orale</em> / <em>écrite</em>, also to an NCLC level (1–12).
                </p>

                <!-- Per-question points -->
                <h4 class="mt-6 mb-2 text-sm font-bold tracking-wider uppercase text-[#003d7a]">Points per question</h4>
                <div class="overflow-hidden rounded-xl border border-gray-200">
                  <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Questions</th>
                        <th class="px-3 py-2 text-right font-semibold text-gray-700">Points each</th>
                        <th class="px-3 py-2 text-right font-semibold text-gray-700 hidden sm:table-cell">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      <tr><td class="px-3 py-2">Q1 – Q4 (4 questions)</td><td class="px-3 py-2 text-right tabular-nums">3 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">12</td></tr>
                      <tr><td class="px-3 py-2">Q5 – Q10 (6 questions)</td><td class="px-3 py-2 text-right tabular-nums">9 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">54</td></tr>
                      <tr><td class="px-3 py-2">Q11 – Q19 (9 questions)</td><td class="px-3 py-2 text-right tabular-nums">15 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">135</td></tr>
                      <tr><td class="px-3 py-2">Q20 – Q29 (10 questions)</td><td class="px-3 py-2 text-right tabular-nums">21 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">210</td></tr>
                      <tr><td class="px-3 py-2">Q30 – Q35 (6 questions)</td><td class="px-3 py-2 text-right tabular-nums">26 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">156</td></tr>
                      <tr><td class="px-3 py-2">Q36 – Q39 (4 questions)</td><td class="px-3 py-2 text-right tabular-nums">33 PTS</td><td class="px-3 py-2 text-right tabular-nums text-gray-500 hidden sm:table-cell">132</td></tr>
                      <tr class="bg-gray-50 font-bold">
                        <td class="px-3 py-2">Total (39 questions)</td>
                        <td class="px-3 py-2 text-right tabular-nums">—</td>
                        <td class="px-3 py-2 text-right tabular-nums hidden sm:table-cell">699</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- CEFR / TCF level table -->
                <h4 class="mt-6 mb-2 text-sm font-bold tracking-wider uppercase text-[#003d7a]">CEFR / TCF levels</h4>
                <div class="overflow-hidden rounded-xl border border-gray-200">
                  <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Level</th>
                        <th class="px-3 py-2 text-right font-semibold text-gray-700">Score range (/699)</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      <tr><td class="px-3 py-2 font-semibold">A1</td><td class="px-3 py-2 text-right tabular-nums">100 – 199</td></tr>
                      <tr><td class="px-3 py-2 font-semibold">A2</td><td class="px-3 py-2 text-right tabular-nums">200 – 299</td></tr>
                      <tr><td class="px-3 py-2 font-semibold">B1</td><td class="px-3 py-2 text-right tabular-nums">300 – 399</td></tr>
                      <tr><td class="px-3 py-2 font-semibold">B2</td><td class="px-3 py-2 text-right tabular-nums">400 – 499</td></tr>
                      <tr><td class="px-3 py-2 font-semibold">C1</td><td class="px-3 py-2 text-right tabular-nums">500 – 599</td></tr>
                      <tr><td class="px-3 py-2 font-semibold">C2</td><td class="px-3 py-2 text-right tabular-nums">600 – 699</td></tr>
                    </tbody>
                  </table>
                </div>

                <!-- NCLC table — only for orale/written -->
                <template v-if="currentGroupResult && currentGroupResult.nclcBars && currentGroupResult.nclcBars.length">
                  <h4 class="mt-6 mb-2 text-sm font-bold tracking-wider uppercase text-[#003d7a]">
                    NCLC levels <span class="text-gray-500 font-normal normal-case tracking-normal">— for {{ examPrep?.exam_prep_category === 'orale' ? 'Compréhension orale (Listening)' : 'Compréhension écrite (Reading)' }}</span>
                  </h4>
                  <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="w-full text-sm">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-3 py-2 text-left font-semibold text-gray-700">NCLC</th>
                          <th class="px-3 py-2 text-right font-semibold text-gray-700">Score range (/699)</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100">
                        <tr v-for="r in currentGroupResult.nclcBars" :key="r.label">
                          <td class="px-3 py-2 font-semibold">{{ r.label }}</td>
                          <td class="px-3 py-2 text-right tabular-nums">{{ r.range }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </template>

                <p class="text-xs text-gray-500 mt-4 italic">
                  NCLC ranges differ slightly between Listening and Reading comprehension per the official TCF Canada conversion tables.
                </p>
              </div>
            </div>
          </div>

          <!-- Review mode: all questions stacked with answers revealed -->
          <div v-else-if="reviewMode && selectedGroup" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
              <button
                @click="exitReviewMode"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-200 hover:border-[#0055A4] hover:text-[#0055A4] text-sm font-medium text-gray-700 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to results
              </button>
              <p class="text-sm text-gray-500">Reviewing answers for <span class="font-semibold text-gray-800">{{ selectedGroupKey }}</span></p>
            </div>

            <div
              v-for="(sec, sIdx) in selectedGroup.sections"
              :key="sIdx"
              class="bg-white rounded-2xl border border-gray-200 overflow-hidden"
            >
              <!-- Section header -->
              <div class="px-5 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="font-bold text-gray-800">{{ sIdx + 1 }}.</span>
                  <span class="font-semibold text-gray-800">{{ sec.title || sec.sectionTitle || `Question ${sIdx + 1}` }}</span>
                </div>
                <span v-if="sec.category" class="text-xs px-2.5 py-1 bg-[#0055A4]/10 text-[#003d7a] rounded-full">
                  {{ sec.category }}
                </span>
              </div>

              <div class="p-5 space-y-4">
                <!-- Reading text -->
                <div
                  v-if="sec.text || sec.content || sec.description"
                  class="bg-gray-50 rounded-xl p-4 md:p-5 border border-gray-200 text-gray-800 whitespace-pre-wrap text-base leading-relaxed"
                >{{ sec.text || sec.content || sec.description }}</div>

                <!-- Audio (lazy) -->
                <div v-if="Array.isArray(sec.audio) && sec.audio.length > 0" class="space-y-2">
                  <AudioPlayer
                    v-for="(audioFile, ai) in sec.audio"
                    :key="ai"
                    :src="getAudioUrl(audioFile)"
                  />
                </div>

                <!-- Image(s) attached to the section -->
                <div v-if="Array.isArray(sec.image) && sec.image.length > 0" class="grid gap-3" :class="sec.image.length > 1 ? 'sm:grid-cols-2' : ''">
                  <div
                    v-for="(imgPath, ii) in sec.image"
                    :key="ii"
                    class="bg-gray-50 rounded-xl border border-[#0055A4]/30 p-2 overflow-hidden"
                  >
                    <img
                      :src="getImageUrl(imgPath)"
                      :alt="`Section image ${ii + 1}`"
                      class="w-full h-auto object-contain rounded-lg max-h-[500px] mx-auto"
                      loading="lazy"
                    />
                  </div>
                </div>

                <!-- Questions -->
                <div
                  v-for="(q, qIdx) in (sec.questions || [])"
                  :key="qIdx"
                  class="rounded-xl bg-white border border-gray-100 p-4 md:p-5"
                >
                  <p class="font-semibold text-gray-800 mb-4">
                    {{ qIdx + 1 }}. {{ q.question || q.text || `Question ${qIdx + 1}` }}
                  </p>
                  <div class="space-y-2.5">
                    <div
                      v-for="(opt, oIdx) in (q.options || [])"
                      :key="oIdx"
                      class="flex items-center gap-3 p-3 rounded-xl border-2"
                      :class="reviewOptionClass(selectedGroup.startIndex + sIdx, qIdx, oIdx, sec.questions)"
                    >
                      <!-- Status indicator -->
                      <span
                        v-if="reviewIconKind(selectedGroup.startIndex + sIdx, qIdx, oIdx, sec.questions) === 'correct'"
                        class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0"
                      >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                      </span>
                      <span
                        v-else-if="reviewIconKind(selectedGroup.startIndex + sIdx, qIdx, oIdx, sec.questions) === 'wrong'"
                        class="w-7 h-7 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0"
                      >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                      </span>
                      <span
                        v-else
                        class="w-7 h-7 rounded-full border-2 border-gray-300 flex-shrink-0"
                      ></span>

                      <span class="font-medium">
                        {{ typeof opt === 'object' ? (opt.text || opt.option || '') : opt }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-end pt-2">
              <button
                @click="exitReviewMode"
                class="px-5 py-2.5 rounded-xl bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium text-sm transition-colors"
              >
                Back to results
              </button>
            </div>
          </div>

          <!-- Quiz interactive content (hidden once results panel is showing) -->
          <div v-if="!showResults && !reviewMode">

          <!-- Timer banner -->
          <div class="bg-[#0055A4]/15 border-l-4 border-[#0055A4] py-3 px-5 mb-5 flex items-center justify-center font-semibold text-gray-800 rounded-r-lg" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 8px, rgba(0, 85, 164,0.05) 8px, rgba(0, 85, 164,0.05) 16px);">
            <svg class="w-5 h-5 mr-2 text-[#0055A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Time limit: <span class="ml-2 tabular-nums">{{ formatHMS(timeRemaining) }}</span>
          </div>

          <!-- Question palette grid -->
          <div class="border-2 border-[#0055A4] rounded-xl bg-white p-4 mb-3 max-h-44 overflow-y-auto">
            <div class="grid gap-2" style="grid-template-columns: repeat(auto-fill, minmax(2.5rem, 1fr));">
              <button
                v-for="(_, localIdx) in (selectedGroup?.sections || [])"
                :key="localIdx"
                @click="goToQuestionLocal(localIdx)"
                :class="paletteClass(localIdx)"
                class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-sm font-semibold transition-all"
              >
                {{ localIdx + 1 }}
              </button>
            </div>
          </div>

          <!-- Legend + review button -->
          <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-600 mb-4">
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full border-2 border-[#0055A4] bg-white"></span> actuel</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span> révision</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-[#0055A4]"></span> répondue</span>
          </div>

          <button
            @click="toggleReview(currentIndex)"
            class="px-5 py-2.5 rounded-full text-white font-medium text-sm transition-colors"
            :class="markedForReview[currentIndex] ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-800 hover:bg-gray-900'"
          >
            {{ markedForReview[currentIndex] ? 'Unmark Review' : 'Review Question' }}
          </button>

          <hr class="my-6 border-gray-200" />

          <!-- Current section content -->
          <div v-if="currentSection">
            <!-- Reading text (boxed) -->
            <div
              v-if="currentSectionText"
              class="bg-gray-50 rounded-2xl p-6 md:p-8 mb-6 border-2 border-[#0055A4]/40 text-lg md:text-2xl leading-relaxed whitespace-pre-wrap text-gray-800"
            >{{ currentSectionText }}</div>

            <!-- Image(s) attached to the section -->
            <div v-if="Array.isArray(currentSection.image) && currentSection.image.length > 0" class="mb-6 grid gap-3" :class="currentSection.image.length > 1 ? 'sm:grid-cols-2' : ''">
              <div
                v-for="(imgPath, ii) in currentSection.image"
                :key="ii"
                class="bg-gray-50 rounded-xl border border-[#0055A4]/30 p-2 overflow-hidden"
              >
                <img
                  :src="getImageUrl(imgPath)"
                  :alt="`Section image ${ii + 1}`"
                  class="w-full h-auto object-contain rounded-lg max-h-[500px] mx-auto"
                  loading="lazy"
                />
              </div>
            </div>

            <!-- Audio (lazy) — placed below the image so users see context first -->
            <div v-if="Array.isArray(currentSection.audio) && currentSection.audio.length > 0" class="mb-6 space-y-2">
              <AudioPlayer
                v-for="(audioFile, ai) in currentSection.audio"
                :key="ai"
                :src="getAudioUrl(audioFile)"
              />
            </div>

            <!-- Question + options -->
            <div v-if="currentQuestion" class="mt-2">
              <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl border-2 border-[#0055A4] flex items-center justify-center text-xl font-bold text-[#0055A4] flex-shrink-0">
                  {{ localIndex + 1 }}
                </div>
                <p class="text-lg md:text-xl text-gray-900 leading-relaxed pt-2">
                  {{ currentQuestion.question || currentQuestion.text || `Question ${currentIndex + 1}` }}
                </p>
              </div>

              <div class="space-y-3">
                <label
                  v-for="(opt, oIdx) in (currentQuestion.options || [])"
                  :key="oIdx"
                  class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all"
                  :class="getQuizOptionClass(oIdx)"
                >
                  <input
                    type="radio"
                    :name="`q-${currentIndex}`"
                    :value="oIdx"
                    v-model.number="userAnswers[`${currentIndex}-0`]"
                    @change="handleAnswerSelect(currentIndex, 0)"
                    class="sr-only"
                  />
                  <span
                    class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-sm font-bold flex-shrink-0"
                    :class="userAnswers[`${currentIndex}-0`] === oIdx ? 'border-[#0055A4] bg-[#0055A4] text-white' : 'border-gray-300 text-gray-600 bg-white'"
                  >
                    {{ ['A','B','C','D','E','F'][oIdx] }}
                  </span>
                  <span class="text-base text-gray-800">
                    {{ typeof opt === 'object' ? (opt.text || opt.option || '') : opt }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Fallback when section has no MCQ (essay/scenario/etc.) -->
            <div
              v-else-if="!currentSectionText && !currentSection.audio"
              class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-800 text-sm"
            >
              No interactive content for this section. Click <em>suivant</em> to continue.
            </div>
          </div>

          <!-- Bottom navigation -->
          <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <button
              @click="prevQuestion"
              :disabled="localIndex === 0"
              class="px-5 py-2.5 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-40 disabled:cursor-not-allowed text-gray-700 font-medium text-sm transition-colors"
            >
              ← précédent
            </button>
            <span class="text-sm text-gray-500 tabular-nums">{{ localIndex + 1 }} / {{ groupSectionsCount }}</span>
            <button
              v-if="currentGroupAllAnswered || localIndex === groupSectionsCount - 1"
              @click="finishTest"
              class="px-6 py-2.5 rounded-full bg-green-600 hover:bg-green-700 text-white font-medium text-sm transition-colors"
            >
              Show Results
            </button>
            <button
              v-else
              @click="nextQuestion"
              class="px-6 py-2.5 rounded-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-medium text-sm transition-colors"
            >
              suivant →
            </button>
          </div>

          </div><!-- /.quiz interactive content (v-if !showResults) -->

          </div><!-- /.quiz-view (selected test) -->
        </div>

      </div>

      <!-- Floating zoom controls — only for "orale" category, only while a test is selected -->
      <div
        v-if="isOraleCategory && selectedGroupKey"
        class="fixed bottom-6 right-6 z-50 flex flex-col items-stretch gap-1 bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden"
      >
        <button
          @click="zoomIn"
          :disabled="quizZoom >= ZOOM_MAX"
          aria-label="Zoom in"
          title="Zoom in"
          class="w-12 h-12 flex items-center justify-center text-[#003d7a] hover:bg-[#0055A4] hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
        </button>
        <button
          @click="resetZoom"
          aria-label="Reset zoom"
          title="Reset zoom"
          class="px-2 py-1 text-[10px] font-bold tracking-wider text-gray-500 hover:text-[#003d7a] hover:bg-gray-50 border-y border-gray-100 tabular-nums"
        >
          {{ Math.round(quizZoom * 100) }}%
        </button>
        <button
          @click="zoomOut"
          :disabled="quizZoom <= ZOOM_MIN"
          aria-label="Zoom out"
          title="Zoom out"
          class="w-12 h-12 flex items-center justify-center text-[#003d7a] hover:bg-[#0055A4] hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
          </svg>
        </button>
      </div>
    </div>

    <div v-else class="flex items-center justify-center h-screen">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Exam Prep Not Found</h1>
        <button
          @click="goBack"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
        >
          Back
        </button>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AudioPlayer from '../components/AudioPlayer.vue'
import { Loader } from 'lucide-vue-next'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useSettingsStore } from '../stores/settings'
import { useToast } from '../composables/useToast'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const settingsStore = useSettingsStore()
const toast = useToast()

const logoUrl = computed(() => {
  return settingsStore.siteLogo
    ? `${import.meta.env.VITE_API_URL}/storage/${settingsStore.siteLogo}`
    : ''
})

const backRootPath = computed(() => {
  const p = route.path || ''
  if (p.startsWith('/student/')) return '/student/exam-prep'
  if (p.startsWith('/tutor/')) return '/tutor/exam-prep'
  return '/admin/exam-prep'
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
const examPrep = ref(null)
const isLocked = ref(false)
const examPrepSections = ref([])
const expandedSections = ref({})

// ----- Quiz-style navigation state (one question at a time) -----
// Two-level navigation: pick a "test" group first → then quiz within that group.
// `currentIndex` is the ABSOLUTE index in `examPrepSections` so existing answer
// keys (`${currentIndex}-0`) keep working with the persistence + scoring code.
const selectedGroupKey = ref(null)
const currentIndex = ref(0)
const markedForReview = ref({})
const TEST_DURATION_SECONDS = 60 * 60
const timeRemaining = ref(TEST_DURATION_SECONDS)
let timerInterval = null
const showResults = ref(false)
const reviewMode = ref(false)
const finalResult = ref({ correct: 0, total: 0, percentage: 0 })
// Collapsible "How is this scored?" panel at the bottom of the result view.
// Closed by default; per-session local UI state (not persisted).
const scoringInfoOpen = ref(false)
// Per-test timer state so each test remembers its own remaining time
// across "Back to tests" and reopens.
const groupTimers = ref({}) // { groupKey: secondsRemaining }

// --- Oral / Written Expression special formats
// Two supported shapes — both opt in via the "Expression" category:
//   A) parties: { parties: [{ partie, scenarios: [{ title, questions:[] }] }] }
//      → role-play scenarios with practice questions
//   B) essais:  { categorie, essais: [{ sujet, correction }] }
//      → essay topics with a model correction shown on expand
// No MCQ, no scoring, no timer — pure reference content shown as collapsibles.
const oralParties = computed(() => {
  const list = []
  for (const s of examPrepSections.value) {
    if (Array.isArray(s.parties)) {
      for (const p of s.parties) list.push(p)
    }
  }
  return list
})
const oralEssais = computed(() => {
  const list = []
  for (const s of examPrepSections.value) {
    if (Array.isArray(s.essais)) {
      for (const e of s.essais) list.push(e)
    }
  }
  return list
})
const oralLayoutChoice = computed(() => {
  const v = (examPrep.value?.exam_prep_oral_layout || '').toString().toLowerCase().trim()
  return v === 'parties' || v === 'essais' ? v : ''
})
// Render priority for the parties view inside the Expression layout:
//   1. Admin explicitly picked "Layout 1 (parties)" → always parties view (empty state if no data)
//   2. Admin didn't pick → fall back to data shape (parties[] present)
const hasOralParties = computed(() => {
  if (oralLayoutChoice.value === 'parties') return true
  if (oralLayoutChoice.value === 'essais') return false
  return oralParties.value.length > 0
})
// Same for the essais view:
const hasOralEssais = computed(() => {
  if (oralLayoutChoice.value === 'essais') return true
  if (oralLayoutChoice.value === 'parties') return false
  return oralEssais.value.length > 0
})
// Detection priority for switching to the Expression layout at all:
// 1. Admin selected an explicit Layout (parties|essais) in the backend → always
//    render the Expression layout (admin gets a clear empty state if the JSON
//    hasn't been populated yet).
// 2. Admin selected "Oral Expression" / "Written Expression" / similar category
//    → also render the Expression layout.
// 3. As a fallback, detect by data shape — parties[] or essais[] in the JSON.
const isOralExpressionFormat = computed(() => {
  if (oralLayoutChoice.value) return true
  const cat = (examPrep.value?.exam_prep_category || '').toString().toLowerCase().trim()
  // Written Expression has its own dedicated layout — don't claim it here
  if (cat === 'written_expression') return false
  if (cat.includes('expression')) return true
  return oralParties.value.length > 0 || oralEssais.value.length > 0
})
// Browser-style zoom (only for the Orale comprehension category)
const isOraleCategory = computed(() => {
  return (examPrep.value?.exam_prep_category || '').toString().toLowerCase().trim() === 'orale'
})
const quizZoom = ref(1)
const ZOOM_STEP = 0.1
const ZOOM_MIN = 0.6
const ZOOM_MAX = 1.6
const zoomIn = () => {
  quizZoom.value = Math.min(ZOOM_MAX, Math.round((quizZoom.value + ZOOM_STEP) * 100) / 100)
}
const zoomOut = () => {
  quizZoom.value = Math.max(ZOOM_MIN, Math.round((quizZoom.value - ZOOM_STEP) * 100) / 100)
}
const resetZoom = () => { quizZoom.value = 1 }
const selectedPartieIndex = ref(null) // index into oralParties.value, or null = show parties list
const expandedScenarioKey = ref(null) // "p{partieIdx}-s{scenarioIdx}" of the currently open scenario
const expandedEssaiKey = ref(null) // "e{essaiIdx}" of the currently open essai

const goToPartie = (idx) => {
  selectedPartieIndex.value = idx
  expandedScenarioKey.value = null
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const backToParties = () => {
  selectedPartieIndex.value = null
  expandedScenarioKey.value = null
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const togglePartieScenario = (pIdx, sIdx) => {
  const k = `p${pIdx}-s${sIdx}`
  expandedScenarioKey.value = expandedScenarioKey.value === k ? null : k
}
// Scenario questions start blurred; click reveals them. Key = partie + scenario index.
// "?" help modal on the Partie selector — shows the admin-entered description
// for Oral Expression exam preps. Closed by default.
const descriptionModalOpen = ref(false)
const revealedScenarioQuestions = ref({})
const scenarioQuestionsKey = (sIdx) => `p${selectedPartieIndex.value}-s${sIdx}`
const isScenarioQuestionsRevealed = (sIdx) => !!revealedScenarioQuestions.value[scenarioQuestionsKey(sIdx)]
const revealScenarioQuestions = (sIdx) => {
  revealedScenarioQuestions.value[scenarioQuestionsKey(sIdx)] = true
}
const toggleOralEssai = (idx) => {
  const k = `e${idx}`
  expandedEssaiKey.value = expandedEssaiKey.value === k ? null : k
}

// "Mark as done" state for each Oral Expression partie. Persisted to DB so it
// survives refresh and follows the user across devices. Key = partie index.
const partieDone = ref({})
const isPartieDone = (pIdx) => !!partieDone.value[pIdx]
const isCurrentPartieDone = computed(() => {
  if (selectedPartieIndex.value === null) return false
  return isPartieDone(selectedPartieIndex.value)
})
const togglePartieDone = () => {
  const pIdx = selectedPartieIndex.value
  if (pIdx === null) return
  const next = { ...partieDone.value }
  if (next[pIdx]) delete next[pIdx]
  else next[pIdx] = true
  partieDone.value = next
}
const essaisCategorie = computed(() => {
  for (const s of examPrepSections.value) {
    if (s.categorie) return s.categorie
  }
  return ''
})

// --- Written Expression format: { month, combinaisons: [{ number, title, tasks: [...] }] }
// Each task has { number, title, correction_title, prompt, correction, word_limit }
// Task 3 of each combinaison can also have: { subject, document_1, document_2 }
//
// Three-level navigation:
//   sections (months) → combinaisons → tâches
// If only one section exists, the section selector is skipped automatically.
const writtenSections = computed(() =>
  examPrepSections.value.filter(s => Array.isArray(s.combinaisons) && s.combinaisons.length > 0)
)
const hasCombinaisons = computed(() => writtenSections.value.length > 0)
const isWrittenExpressionFormat = computed(() => {
  const cat = (examPrep.value?.exam_prep_category || '').toString().toLowerCase().trim()
  if (cat === 'written_expression') return true
  // Data-shape fallback for backward compat
  return hasCombinaisons.value
})
// null = show section grid; index = inside that section
const selectedWrittenSectionIndex = ref(null)
// null = show combinaisons grid; index = inside that combinaison
const selectedCombinaisonIndex = ref(null)
// Effective section index — auto-pick the only section when there's just one,
// so we skip the (useless) one-card selector for single-section JSONs.
const effectiveSectionIndex = computed(() => {
  if (selectedWrittenSectionIndex.value !== null) return selectedWrittenSectionIndex.value
  if (writtenSections.value.length === 1) return 0
  return null
})
const currentWrittenSection = computed(() =>
  effectiveSectionIndex.value !== null ? writtenSections.value[effectiveSectionIndex.value] : null
)
const currentCombinaisons = computed(() =>
  currentWrittenSection.value?.combinaisons || []
)
const currentCombinaison = computed(() =>
  selectedCombinaisonIndex.value !== null
    ? currentCombinaisons.value[selectedCombinaisonIndex.value]
    : null
)
const showWrittenSectionSelector = computed(() =>
  writtenSections.value.length > 1 && selectedWrittenSectionIndex.value === null
)
const goToWrittenSection = (idx) => {
  selectedWrittenSectionIndex.value = idx
  selectedCombinaisonIndex.value = null
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const backToWrittenSections = () => {
  selectedWrittenSectionIndex.value = null
  selectedCombinaisonIndex.value = null
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const goToCombinaison = (idx) => {
  selectedCombinaisonIndex.value = idx
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const backToCombinaisons = () => {
  selectedCombinaisonIndex.value = null
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
// Corrections start blurred; click reveals them. Key = section-combinaison-task index.
const revealedCorrections = ref({})
const correctionKey = (tIdx) => `${effectiveSectionIndex.value}-${selectedCombinaisonIndex.value}-${tIdx}`
const isCorrectionRevealed = (tIdx) => !!revealedCorrections.value[correctionKey(tIdx)]
const revealCorrection = (tIdx) => {
  revealedCorrections.value[correctionKey(tIdx)] = true
}

// "Mark as done" state for each combinaison. Persisted to DB so it survives
// refresh and follows the user across devices. Key = "sectionIdx-combIdx".
const writtenDone = ref({})
const writtenDoneKey = (sIdx, cIdx) => `${sIdx}-${cIdx}`
const isCombinaisonDone = (sIdx, cIdx) => !!writtenDone.value[writtenDoneKey(sIdx, cIdx)]
const isCurrentCombinaisonDone = computed(() => {
  const sIdx = effectiveSectionIndex.value
  const cIdx = selectedCombinaisonIndex.value
  if (sIdx === null || cIdx === null) return false
  return isCombinaisonDone(sIdx, cIdx)
})
const toggleCombinaisonDone = () => {
  const sIdx = effectiveSectionIndex.value
  const cIdx = selectedCombinaisonIndex.value
  if (sIdx === null || cIdx === null) return
  const k = writtenDoneKey(sIdx, cIdx)
  const next = { ...writtenDone.value }
  if (next[k]) delete next[k]
  else next[k] = true
  writtenDone.value = next
}

// "Completed in Xs" pill on the results panel — derived from the selected
// group's own remaining time, so each test shows its own time and nothing
// goes stale when switching between tests.
const timeTakenSeconds = computed(() => {
  const key = selectedGroupKey.value
  if (!key) return 0
  const remaining = groupTimers.value[key]
  if (typeof remaining !== 'number') return 0
  return Math.max(0, TEST_DURATION_SECONDS - remaining)
})

const groupAllAnswered = (group) => {
  if (!group) return false
  for (let i = 0; i < group.sections.length; i++) {
    const sec = group.sections[i]
    const absIdx = group.startIndex + i
    const qCount = Array.isArray(sec.questions) ? sec.questions.length : 0
    if (qCount === 0) continue
    for (let q = 0; q < qCount; q++) {
      const ua = userAnswers.value[`${absIdx}-${q}`]
      if (ua === undefined || ua === null) return false
    }
  }
  return true
}

const remainingUnanswered = (group) => {
  if (!group) return 0
  let n = 0
  for (let i = 0; i < group.sections.length; i++) {
    const sec = group.sections[i]
    const absIdx = group.startIndex + i
    const qCount = Array.isArray(sec.questions) ? sec.questions.length : 0
    if (qCount === 0) continue
    for (let q = 0; q < qCount; q++) {
      const ua = userAnswers.value[`${absIdx}-${q}`]
      if (ua === undefined || ua === null) n++
    }
  }
  return n
}

const selectedGroup = computed(() =>
  selectedGroupKey.value ? groupedSections.value[selectedGroupKey.value] : null
)
const groupStartIndex = computed(() => selectedGroup.value?.startIndex ?? 0)
const groupSectionsCount = computed(() => selectedGroup.value?.sections?.length ?? 0)
const localIndex = computed(() => currentIndex.value - groupStartIndex.value)
// True when every question in the current test has an answer (regardless of
// which question the user is currently viewing). Drives the Show Results
// button so the user can submit from any position once they're done.
const currentGroupAllAnswered = computed(() => groupAllAnswered(selectedGroup.value))

const currentSection = computed(() => examPrepSections.value[currentIndex.value] || null)
const currentQuestion = computed(() => currentSection.value?.questions?.[0] || null)
const currentSectionText = computed(() => {
  const s = currentSection.value
  if (!s) return ''
  return s.text || s.content || s.description || ''
})

const currentGroupResult = computed(() =>
  selectedGroupKey.value ? groupResults.value[selectedGroupKey.value] : null
)

const pointsTier = (points) => {
  // Map a question's point value to one of 6 brand-orange shades for the grid
  if (points <= 3) return { bg: 'bg-[#f4e4d2]', text: 'text-gray-800' }
  if (points <= 9) return { bg: 'bg-[#e6c89c]', text: 'text-gray-800' }
  if (points <= 15) return { bg: 'bg-[#d4a266]', text: 'text-white' }
  if (points <= 21) return { bg: 'bg-[#003d7a]', text: 'text-white' }
  if (points <= 26) return { bg: 'bg-[#8c5a2a]', text: 'text-white' }
  return { bg: 'bg-[#5e3d1c]', text: 'text-white' }
}

const groupAnsweredCount = (group) => {
  if (!group) return 0
  let count = 0
  for (let i = 0; i < group.sections.length; i++) {
    const absIdx = group.startIndex + i
    if (userAnswers.value[`${absIdx}-0`] !== undefined && userAnswers.value[`${absIdx}-0`] !== null) {
      count++
    }
  }
  return count
}

const groupProgressPercent = (group) => {
  if (!group || group.sections.length === 0) return 0
  return Math.round((groupAnsweredCount(group) / group.sections.length) * 100)
}

const stopTimer = () => {
  if (timerInterval) {
    clearInterval(timerInterval)
    timerInterval = null
  }
}

// Find the first question in the group that the user hasn't answered yet
// (or -1 if all answered). Used to land "Continue" on the right question.
const firstUnansweredLocalIdx = (group) => {
  if (!group) return 0
  for (let i = 0; i < group.sections.length; i++) {
    const absIdx = group.startIndex + i
    const sec = group.sections[i]
    const qCount = Array.isArray(sec.questions) ? sec.questions.length : 0
    if (qCount === 0) continue // skip sections with no MCQ (e.g. essay-only)
    for (let q = 0; q < qCount; q++) {
      const ua = userAnswers.value[`${absIdx}-${q}`]
      if (ua === undefined || ua === null) return i
    }
  }
  return -1
}

const goToTest = (groupKey, { fromUrl = false } = {}) => {
  const group = groupedSections.value[groupKey]
  if (!group) return
  selectedGroupKey.value = groupKey
  // Jump to the first unanswered question so "Continue" lands where the user
  // left off instead of always resetting to question 1. If everything is
  // answered, we'll show the results panel below anyway.
  const nextLocal = firstUnansweredLocalIdx(group)
  currentIndex.value = group.startIndex + (nextLocal >= 0 ? nextLocal : 0)
  reviewMode.value = false
  // Sync the test pick to the URL so a refresh restores the same test.
  if (!fromUrl && route.query.test !== groupKey) {
    router.replace({ query: { ...route.query, test: groupKey } }).catch(() => {})
  }
  // If the test was already completed before, jump straight to the results panel
  // (no countdown, no quiz). User can still hit Review / Try Again from there.
  if (groupAllAnswered(group)) {
    stopTimer()
    // Make sure this completed test has a groupTimers entry so the
    // "Completed in Xs" pill can derive the time. If for some reason it
    // wasn't captured (e.g. legacy state), default to 0 elapsed.
    if (typeof groupTimers.value[groupKey] !== 'number') {
      groupTimers.value = { ...groupTimers.value, [groupKey]: TEST_DURATION_SECONDS }
    }
    showGroupResults(groupKey, group)
    showResults.value = true
  } else {
    showResults.value = false
    stopTimer()
    // Resume timer from this group's saved value (per-test).
    // Only start fresh at 60:00 if this group has no saved timer yet.
    const saved = groupTimers.value[groupKey]
    timeRemaining.value = (typeof saved === 'number' && saved > 0 && saved <= TEST_DURATION_SECONDS)
      ? saved
      : TEST_DURATION_SECONDS
    startTimer()
  }
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const goToTestList = () => {
  selectedGroupKey.value = null
  showResults.value = false
  reviewMode.value = false
  // Freeze the timer for whichever test we were on — its remaining time is
  // already saved in groupTimers, so resume will pick it up next time.
  stopTimer()
  // Remove the test query param so refresh lands on the selector again.
  if (route.query.test) {
    const { test, ...rest } = route.query
    router.replace({ query: rest }).catch(() => {})
  }
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const goToQuestionLocal = (localIdx) => {
  if (!selectedGroup.value) return
  if (localIdx < 0 || localIdx >= groupSectionsCount.value) return
  currentIndex.value = groupStartIndex.value + localIdx
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const isCurrentQuestionAnswered = () => {
  const sec = currentSection.value
  if (!sec) return true
  // No interactive questions (e.g. text-only / essay sections) — let the user pass.
  if (!Array.isArray(sec.questions) || sec.questions.length === 0) return true
  for (let q = 0; q < sec.questions.length; q++) {
    const ua = userAnswers.value[`${currentIndex.value}-${q}`]
    if (ua === undefined || ua === null) return false
  }
  return true
}

const nextQuestion = () => {
  if (!selectedGroup.value) return
  // Block advance if current question isn't answered.
  if (!isCurrentQuestionAnswered()) {
    if (toast?.warning) {
      toast.warning('You must answer this question.')
    } else if (toast?.error) {
      toast.error('You must answer this question.')
    } else {
      alert('You must answer this question.')
    }
    return
  }
  if (localIndex.value < groupSectionsCount.value - 1) {
    currentIndex.value++
    nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
  }
}
const prevQuestion = () => {
  if (!selectedGroup.value) return
  if (localIndex.value > 0) {
    currentIndex.value--
    nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
  }
}

const toggleReview = (idx) => {
  markedForReview.value = { ...markedForReview.value, [idx]: !markedForReview.value[idx] }
}

// Palette receives a LOCAL index (0..groupSectionsCount-1); maps it to absolute
// for answer-state lookups.
const paletteClass = (localIdx) => {
  const absIdx = groupStartIndex.value + localIdx
  const isCurrent = absIdx === currentIndex.value
  const answered = userAnswers.value[`${absIdx}-0`] !== undefined && userAnswers.value[`${absIdx}-0`] !== null
  const review = !!markedForReview.value[absIdx]
  if (isCurrent) return 'border-[#0055A4] bg-white text-[#0055A4] ring-2 ring-[#0055A4]/30'
  if (review) return 'border-yellow-400 bg-yellow-400 text-white'
  if (answered) return 'border-[#0055A4] bg-[#0055A4] text-white'
  return 'border-gray-300 bg-white text-gray-600 hover:border-[#0055A4]'
}

const getQuizOptionClass = (oIdx) => {
  const sel = userAnswers.value[`${currentIndex.value}-0`] === oIdx
  return sel
    ? 'border-[#0055A4] bg-[#0055A4]/10'
    : 'border-gray-200 hover:border-[#0055A4]/50 hover:bg-gray-50'
}

const formatDurationShort = (s) => {
  // "23m 45s" / "59s" / "1h 04m" — readable for completion time display.
  if (s <= 0) return '0s'
  const h = Math.floor(s / 3600)
  const m = Math.floor((s % 3600) / 60)
  const sec = s % 60
  if (h > 0) return `${h}h ${m.toString().padStart(2, '0')}m`
  if (m > 0) return `${m}m ${sec.toString().padStart(2, '0')}s`
  return `${sec}s`
}

const formatHMS = (s) => {
  if (s <= 0) return '00:00:00'
  const h = Math.floor(s / 3600)
  const m = Math.floor((s % 3600) / 60)
  const sec = s % 60
  return [h, m, sec].map(v => v.toString().padStart(2, '0')).join(':')
}

const startTimer = () => {
  if (timerInterval) clearInterval(timerInterval)
  timerInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--
      // Persist the remaining time on this group's bucket so it survives
      // navigation away from the test or a page refresh.
      if (selectedGroupKey.value) {
        groupTimers.value = { ...groupTimers.value, [selectedGroupKey.value]: timeRemaining.value }
      }
    } else {
      clearInterval(timerInterval)
      timerInterval = null
      if (!showResults.value) finishTest()
    }
  }, 1000)
}

const finishTest = () => {
  // Score only the SELECTED test, not the whole exam prep.
  const group = selectedGroup.value
  if (!group) return

  // Block completion if any question is still unanswered.
  if (!groupAllAnswered(group)) {
    const remaining = remainingUnanswered(group)
    if (toast?.warning) {
      toast.warning(`You must answer all questions before you can complete the quiz. ${remaining} unanswered.`)
    } else if (toast?.error) {
      toast.error(`You must answer all questions before you can complete the quiz. ${remaining} unanswered.`)
    } else {
      alert(`You must answer all questions before you can complete the quiz. ${remaining} unanswered.`)
    }
    // Jump to the first unanswered question for convenience
    for (let i = 0; i < group.sections.length; i++) {
      const sec = group.sections[i]
      const absIdx = group.startIndex + i
      const qCount = Array.isArray(sec.questions) ? sec.questions.length : 0
      if (qCount === 0) continue
      const ua = userAnswers.value[`${absIdx}-0`]
      if (ua === undefined || ua === null) {
        currentIndex.value = absIdx
        nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
        break
      }
    }
    return
  }

  // Stop the timer — the test is done. timeTakenSeconds is a computed value
  // derived from groupTimers[selectedGroupKey] so it auto-updates without
  // needing to be set here.
  stopTimer()
  // Populate the TCF-scoring panel data (groupResults[groupKey])
  showGroupResults(selectedGroupKey.value, group)
  // Also store the simple correct/total/percentage for any legacy use
  let total = 0
  let correct = 0
  for (let i = 0; i < group.sections.length; i++) {
    const sec = group.sections[i]
    const absIdx = group.startIndex + i
    if (Array.isArray(sec.questions) && sec.questions.length > 0) {
      sec.questions.forEach((q, qIdx) => {
        total++
        const ua = userAnswers.value[`${absIdx}-${qIdx}`]
        const correctIdx = getCorrectAnswerIndex(absIdx, qIdx, sec.questions)
        if (ua !== undefined && ua === correctIdx) correct++
      })
    }
  }
  const percentage = total > 0 ? Math.round((correct / total) * 100) : 0
  finalResult.value = { correct, total, percentage }
  showResults.value = true
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const enterReviewMode = () => {
  reviewMode.value = true
  showResults.value = false
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const exitReviewMode = () => {
  reviewMode.value = false
  showResults.value = true
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}

const reviewOptionClass = (absIdx, qIdx, oIdx, questions) => {
  const userAns = userAnswers.value[`${absIdx}-${qIdx}`]
  const correctIdx = getCorrectAnswerIndex(absIdx, qIdx, questions)
  const isCorrect = oIdx === correctIdx
  const isUserPick = oIdx === userAns
  if (isCorrect) return 'border-green-500 bg-green-50 text-green-800'
  if (isUserPick) return 'border-red-500 bg-red-50 text-red-800'
  return 'border-gray-200 bg-gray-50 text-gray-400'
}

const reviewIconKind = (absIdx, qIdx, oIdx, questions) => {
  const userAns = userAnswers.value[`${absIdx}-${qIdx}`]
  const correctIdx = getCorrectAnswerIndex(absIdx, qIdx, questions)
  if (oIdx === correctIdx) return 'correct'
  if (oIdx === userAns) return 'wrong'
  return 'idle'
}

const restartTest = () => {
  if (!confirm('Are you sure you want to restart this test?\n\nAll your answers and progress for this test will be reset. Other tests will not be affected.')) {
    return
  }
  // Reset only the SELECTED test's answers — other tests' progress stays.
  const group = selectedGroup.value
  if (group) {
    for (let i = 0; i < group.sections.length; i++) {
      const absIdx = group.startIndex + i
      const sec = group.sections[i]
      const qCount = Array.isArray(sec.questions) ? sec.questions.length : 1
      for (let q = 0; q < qCount; q++) {
        delete userAnswers.value[`${absIdx}-${q}`]
        delete answeredQuestions.value[`${absIdx}-${q}`]
      }
      delete sectionResults.value[absIdx]
      delete markedForReview.value[absIdx]
    }
    currentIndex.value = group.startIndex
    // Wipe this test's saved timer so it restarts fresh at 60:00.
    const gKey = selectedGroupKey.value
    if (gKey) {
      const { [gKey]: _, ...rest } = groupTimers.value
      groupTimers.value = rest
    }
  }
  timeRemaining.value = TEST_DURATION_SECONDS
  showResults.value = false
  reviewMode.value = false
  startTimer()
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }))
}
const expandedGroups = ref({})
const expandedTextSections = ref({})
const expandedWordBanks = ref({})
const expandedEssais = ref({})
const expandedScenarios = ref({})
const expandedCombinaisons = ref({})
const expandedCorrections = ref({})
const groupResults = ref({})

const isTcfScoringCategory = computed(() => {
  const cat = examPrep.value?.exam_prep_category?.toLowerCase()
  return cat === 'written' || cat === 'orale'
})

const getQuestionPoints = (qIndex) => {
  if (qIndex < 4) return 3
  if (qIndex < 10) return 9
  if (qIndex < 19) return 15
  if (qIndex < 29) return 21
  if (qIndex < 35) return 26
  return 33
}

const gridColorClass = (points) => {
  switch (points) {
    case 3: return 'bg-[#e8c799]'
    case 9: return 'bg-[#d8aa6e]'
    case 15: return 'bg-[#0055A4]'
    case 21: return 'bg-[#003d7a]'
    case 26: return 'bg-[#8a5a26]'
    case 33: return 'bg-[#4f3415]'
    default: return 'bg-gray-400'
  }
}

const computeLevelBars = (score) => {
  const levels = [
    { label: 'A1', min: 100, max: 199 },
    { label: 'A2', min: 200, max: 299 },
    { label: 'B1', min: 300, max: 399 },
    { label: 'B2', min: 400, max: 499 },
    { label: 'C1', min: 500, max: 599 },
    { label: 'C2', min: 600, max: 699 },
  ]
  return levels.map(lvl => {
    let percent = 0
    let state = 'locked'
    if (score >= lvl.max) {
      percent = 100
      state = 'achieved'
    } else if (score >= lvl.min) {
      percent = Math.round(((score - lvl.min) / (lvl.max - lvl.min)) * 100)
      state = 'current'
    }
    return { ...lvl, range: `${lvl.min}–${lvl.max}`, percent, state }
  })
}

const computeNextGoal = (score) => {
  if (score >= 600) return null
  const targets = [
    { label: 'A1', threshold: 100 },
    { label: 'A2', threshold: 200 },
    { label: 'B1', threshold: 300 },
    { label: 'B2', threshold: 400 },
    { label: 'C1', threshold: 500 },
    { label: 'C2', threshold: 600 },
  ]
  const next = targets.find(t => score < t.threshold)
  if (!next) return null
  return {
    label: next.label,
    pointsNeeded: next.threshold - score,
  }
}

const getTcfLevelLabel = (score) => {
  if (score < 100) return 'A1 non atteint'
  if (score < 200) return 'A1 atteint'
  if (score < 300) return 'A2 atteint'
  if (score < 400) return 'B1 atteint'
  if (score < 500) return 'B2 atteint'
  if (score < 600) return 'C1 atteint'
  return 'C2 atteint'
}

const getTcfLevelInfo = (score) => {
  if (score < 100) return { level: 'A1', status: 'non atteint', achieved: false }
  if (score < 200) return { level: 'A1', status: 'atteint', achieved: true }
  if (score < 300) return { level: 'A2', status: 'atteint', achieved: true }
  if (score < 400) return { level: 'B1', status: 'atteint', achieved: true }
  if (score < 500) return { level: 'B2', status: 'atteint', achieved: true }
  if (score < 600) return { level: 'C1', status: 'atteint', achieved: true }
  return { level: 'C2', status: 'atteint', achieved: true }
}

// NCLC / CLB rating, only applicable to "orale" (compréhension orale) and
// "written" (compréhension écrite) categories — TCF Canada scoring on /699.
// Each category has its own band thresholds. NCLC 1–12 mapping per the
// official TCF Canada conversion tables.
const NCLC_RANGES = {
  // TCF Canada Listening Comprehension (/699)
  orale: [
    { min: 0,   max: 249, label: 'NCLC 1' },
    { min: 250, max: 299, label: 'NCLC 2' },
    { min: 300, max: 330, label: 'NCLC 3' },
    { min: 331, max: 368, label: 'NCLC 4' },
    { min: 369, max: 397, label: 'NCLC 5' },
    { min: 398, max: 457, label: 'NCLC 6' },
    { min: 458, max: 502, label: 'NCLC 7' },
    { min: 503, max: 522, label: 'NCLC 8' },
    { min: 523, max: 548, label: 'NCLC 9' },
    { min: 549, max: 668, label: 'NCLC 10' },
    { min: 669, max: 698, label: 'NCLC 11' },
    { min: 699, max: 699, label: 'NCLC 12' },
  ],
  // TCF Canada Reading Comprehension (/699)
  written: [
    { min: 0,   max: 249, label: 'NCLC 1' },
    { min: 250, max: 299, label: 'NCLC 2' },
    { min: 300, max: 341, label: 'NCLC 3' },
    { min: 342, max: 374, label: 'NCLC 4' },
    { min: 375, max: 405, label: 'NCLC 5' },
    { min: 406, max: 452, label: 'NCLC 6' },
    { min: 453, max: 498, label: 'NCLC 7' },
    { min: 499, max: 523, label: 'NCLC 8' },
    { min: 524, max: 548, label: 'NCLC 9' },
    { min: 549, max: 668, label: 'NCLC 10' },
    { min: 669, max: 698, label: 'NCLC 11' },
    { min: 699, max: 699, label: 'NCLC 12' },
  ],
}
const getNclcRanges = (category) => {
  const cat = (category || '').toString().toLowerCase().trim()
  if (cat === 'orale') return NCLC_RANGES.orale
  if (cat === 'written') return NCLC_RANGES.written
  return null
}
const getNclcLabel = (score, category) => {
  const ranges = getNclcRanges(category)
  if (!ranges) return null
  const found = ranges.find(r => score >= r.min && score <= r.max)
  return found ? found.label : null
}
const formatNclcRange = (r) => r.min === r.max ? `${r.min}` : `${r.min}–${r.max}`
const getNclcInfo = (score, category) => {
  const ranges = getNclcRanges(category)
  if (!ranges) return null
  const found = ranges.find(r => score >= r.min && score <= r.max)
  if (!found) return null
  return { label: found.label, range: formatNclcRange(found), achieved: true }
}
// Bars + next-goal kept for backwards compatibility with older saved progress
// objects. Not currently rendered in the UI (the result panel shows the NCLC
// label inline on the TCF bars and in the achievement card).
const computeNclcBars = (score, category) => {
  const ranges = getNclcRanges(category)
  if (!ranges) return []
  return ranges.map(r => {
    let percent = 0
    let state = 'locked'
    if (score >= r.max) {
      percent = 100
      state = 'achieved'
    } else if (score >= r.min) {
      percent = r.max === r.min ? 100 : Math.round(((score - r.min) / (r.max - r.min)) * 100)
      state = 'current'
    }
    return { ...r, range: formatNclcRange(r), percent, state }
  })
}
const computeNclcNextGoal = (score, category) => {
  const ranges = getNclcRanges(category)
  if (!ranges) return null
  if (score >= ranges[ranges.length - 1].max) return null
  const currentIdx = ranges.findIndex(r => score >= r.min && score <= r.max)
  const next = ranges[currentIdx + 1]
  if (!next) return null
  return { label: next.label, pointsNeeded: next.min - score }
}

const isSectionFullyCorrect = (sectionGlobalIndex) => {
  const section = examPrepSections.value[sectionGlobalIndex]
  if (!section?.questions || section.questions.length === 0) return false
  for (let qi = 0; qi < section.questions.length; qi++) {
    const userAnswer = userAnswers.value[`${sectionGlobalIndex}-${qi}`]
    const correctIndex = getCorrectAnswerIndex(sectionGlobalIndex, qi, section.questions)
    if (userAnswer === undefined || correctIndex === -1 || userAnswer !== correctIndex) return false
  }
  return true
}

const isSectionAnswered = (sectionGlobalIndex) => {
  const section = examPrepSections.value[sectionGlobalIndex]
  if (!section?.questions || section.questions.length === 0) return false
  for (let qi = 0; qi < section.questions.length; qi++) {
    if (userAnswers.value[`${sectionGlobalIndex}-${qi}`] === undefined) return false
  }
  return true
}

const countAnsweredInGroup = (group) => {
  let count = 0
  for (let i = 0; i < group.sections.length; i++) {
    if (isSectionAnswered(group.startIndex + i)) count++
  }
  return count
}

const scrollToResults = (groupKey) => {
  nextTick(() => {
    const el = document.getElementById(`results-${groupKey}`)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  })
}

const goToNextInGroup = (group, currentLocalIndex) => {
  const currentGlobal = group.startIndex + currentLocalIndex
  const nextGlobal = currentGlobal + 1

  expandedSections.value[currentGlobal] = false
  expandedSections.value[nextGlobal] = true

  nextTick(() => {
    const el = document.getElementById(`section-${nextGlobal}`)
    if (el) {
      const offset = 100
      const top = el.getBoundingClientRect().top + window.pageYOffset - offset
      window.scrollTo({ top, behavior: 'smooth' })
    }
  })
}

const showGroupResults = (groupKey, group) => {
  let totalEarned = 0
  let totalPossible = 0
  let correctCount = 0
  const cells = []

  for (let i = 0; i < group.sections.length; i++) {
    const sectionGlobalIndex = group.startIndex + i
    const points = getQuestionPoints(i)
    totalPossible += points

    const correct = isSectionFullyCorrect(sectionGlobalIndex)
    const answered = isSectionAnswered(sectionGlobalIndex)
    if (correct) {
      totalEarned += points
      correctCount++
    }

    cells.push({ qNum: i + 1, points, correct, answered })

    // Lock answers + reveal correct/wrong styling
    const section = examPrepSections.value[sectionGlobalIndex]
    if (section?.questions) {
      for (let qi = 0; qi < section.questions.length; qi++) {
        const key = `${sectionGlobalIndex}-${qi}`
        const userAnswer = userAnswers.value[key]
        const correctIdx = getCorrectAnswerIndex(sectionGlobalIndex, qi, section.questions)
        answeredQuestions.value[key] = {
          selectedAnswer: String(userAnswer ?? ''),
          isCorrect: userAnswer === correctIdx
        }
      }
    }
  }

  const percentage = totalPossible > 0 ? Math.round((totalEarned / totalPossible) * 100) : 0
  const cat = examPrep.value?.exam_prep_category?.toLowerCase()

  const tcfInfo = getTcfLevelInfo(totalEarned)

  groupResults.value[groupKey] = {
    totalEarned,
    totalPossible,
    percentage,
    correctCount,
    totalQuestions: group.sections.length,
    cells,
    levelLabel: getTcfLevelLabel(totalEarned),
    level: tcfInfo.level,
    status: tcfInfo.status,
    achieved: tcfInfo.achieved,
    nclcLabel: getNclcLabel(totalEarned, cat),
    nclcInfo: getNclcInfo(totalEarned, cat),
    nclcBars: computeNclcBars(totalEarned, cat),
    nclcNextGoal: computeNclcNextGoal(totalEarned, cat),
    levelBars: computeLevelBars(totalEarned),
    nextGoal: computeNextGoal(totalEarned),
  }
}

const resetGroupResults = (groupKey, group) => {
  delete groupResults.value[groupKey]
  // Unlock all answers in group sections
  for (let i = 0; i < group.sections.length; i++) {
    const sectionGlobalIndex = group.startIndex + i
    const section = examPrepSections.value[sectionGlobalIndex]
    if (section?.questions) {
      for (let qi = 0; qi < section.questions.length; qi++) {
        const key = `${sectionGlobalIndex}-${qi}`
        delete answeredQuestions.value[key]
        delete userAnswers.value[key]
      }
    }
  }
}

const categoryLabels = {
  written: 'Written',
  orale: 'Orale',
  expression: 'Oral Expression',
  written_expression: 'Written Expression',
}

const formatCategory = (value) => {
  if (!value) return ''
  return categoryLabels[value.toLowerCase()] || value
}

const isCombinaisonOpen = (sectionIndex, cIdx) => {
  const key = `${sectionIndex}-${cIdx}`
  if (expandedCombinaisons.value[key] === undefined) {
    return cIdx === 0
  }
  return expandedCombinaisons.value[key]
}

const toggleCombinaison = (sectionIndex, cIdx) => {
  const key = `${sectionIndex}-${cIdx}`
  expandedCombinaisons.value[key] = !isCombinaisonOpen(sectionIndex, cIdx)
}

const isCorrectionOpen = (sectionIndex, cIdx, tIdx) => {
  return !!expandedCorrections.value[`${sectionIndex}-${cIdx}-${tIdx}`]
}

const toggleCorrection = (sectionIndex, cIdx, tIdx) => {
  const key = `${sectionIndex}-${cIdx}-${tIdx}`
  expandedCorrections.value[key] = !expandedCorrections.value[key]
}

const toggleEssai = (sectionIndex, essaiIndex) => {
  const key = `${sectionIndex}-${essaiIndex}`
  expandedEssais.value[key] = !expandedEssais.value[key]
}

const scenarioKey = (sectionIndex, pIdx, sIdx) => `${sectionIndex}-${pIdx}-${sIdx}`

const isScenarioOpen = (sectionIndex, pIdx, sIdx) => {
  const key = scenarioKey(sectionIndex, pIdx, sIdx)
  if (expandedScenarios.value[key] === undefined) {
    return sIdx === 0
  }
  return expandedScenarios.value[key]
}

const toggleScenario = (sectionIndex, pIdx, sIdx) => {
  const key = scenarioKey(sectionIndex, pIdx, sIdx)
  expandedScenarios.value[key] = !isScenarioOpen(sectionIndex, pIdx, sIdx)
}
const userAnswers = ref({})
const answeredQuestions = ref({})
const sectionResults = ref({})

const examPrepId = route.params.id

const getAssetUrl = (path) => {
  if (!path) return ''
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  if (path.startsWith('/storage/')) return path
  if (path.startsWith('exam-preps/')) return `/storage/${path}`
  return `/storage/exam-preps/${path}`
}

const getImageUrl = getAssetUrl

// Audio routes through a backend streaming endpoint that hides the .mp3
// extension and supports Range requests, so download managers (IDM, etc.)
// don't intercept the request.
const base64UrlEncode = (str) => {
  // Encode UTF-8 string to base64url (no padding)
  const utf8 = unescape(encodeURIComponent(str))
  return btoa(utf8).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '')
}

const getAudioUrl = (path) => {
  if (!path) return ''
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  // Normalize to a relative path under storage/app/public/exam-preps/
  let rel = path
  if (rel.startsWith('/storage/exam-preps/')) rel = rel.slice('/storage/exam-preps/'.length)
  else if (rel.startsWith('storage/exam-preps/')) rel = rel.slice('storage/exam-preps/'.length)
  else if (rel.startsWith('/storage/')) rel = rel.slice('/storage/'.length).replace(/^exam-preps\//, '')
  else if (rel.startsWith('exam-preps/')) rel = rel.slice('exam-preps/'.length)
  rel = rel.replace(/^\/+/, '')

  const token = base64UrlEncode(rel)
  const apiURL = import.meta.env.VITE_API_URL || ''
  return `${apiURL}/api/exam-prep-media/${token}`
}

const getBannerImageUrl = (imagePath) => {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) return imagePath
  const apiURL = import.meta.env.VITE_API_URL
  if (!imagePath.startsWith('/')) return apiURL + '/storage/' + imagePath
  return apiURL + imagePath
}

const groupedSections = computed(() => {
  const grouped = {}
  let currentIndex = 0

  examPrepSections.value.forEach((section) => {
    const groupKey = section.difficulty || section.level || section.category || section.mois || section.categorie || section.month || 'Other Sections'

    if (!grouped[groupKey]) {
      grouped[groupKey] = { sections: [], startIndex: currentIndex, firstSeen: currentIndex }
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
    return grouped[a].firstSeen - grouped[b].firstSeen
  })

  const sortedGroups = {}
  sortedKeys.forEach(key => { sortedGroups[key] = grouped[key] })
  return sortedGroups
})

const fetchExamPrep = async () => {
  try {
    const apiURL = import.meta.env.VITE_API_URL
    const isStudentContext = (route.path || '').startsWith('/student/')
    const endpoint = isStudentContext
      ? `/api/student/exam-preps/${examPrepId}`
      : `/api/exam-preps/${examPrepId}`

    const headers = { 'Accept': 'application/json' }
    if (isStudentContext) {
      const token = localStorage.getItem('token')
      if (token) headers['Authorization'] = `Bearer ${token}`
    }

    const response = await fetch(apiURL + endpoint, { headers, credentials: 'include' })

    if (response.ok) {
      const data = await response.json()
      const wrapper = data.data || data
      const payload = (wrapper && wrapper.examPrep) ? wrapper.examPrep : wrapper
      isLocked.value = isStudentContext ? !!wrapper.is_locked : false

      if ((payload.exam_prep_category === 'books' || payload.exam_prep_category === 'lingopie') && payload.custom_url) {
        const target = payload.custom_url_target || '_blank'
        if (target === '_blank') {
          window.open(payload.custom_url, '_blank')
        } else {
          window.location.href = payload.custom_url
        }
        return
      }

      examPrep.value = payload

      try {
        let jsonContent = examPrep.value.exam_prep_json_content
        if (typeof jsonContent === 'string') {
          jsonContent = JSON.parse(jsonContent)
        }

        if (Array.isArray(jsonContent)) {
          const processed = []
          jsonContent.forEach((section, sectionIndex) => {
            if (section.activities && Array.isArray(section.activities)) {
              section.activities.forEach((activity, activityIndex) => {
                processed.push({
                  ...activity,
                  sectionIndex,
                  activityIndex,
                  category: section.category,
                  difficulty: section.difficulty,
                  sectionTitle: activity.title || `Section ${processed.length + 1}`,
                  questions: activity.questions || []
                })
              })
            } else {
              processed.push({
                ...section,
                sectionIndex,
                sectionTitle: section.section || section.title || `Section ${processed.length + 1}`,
                questions: section.questions || []
              })
            }
          })
          examPrepSections.value = processed
        } else if (jsonContent && jsonContent.activities && Array.isArray(jsonContent.activities)) {
          examPrepSections.value = jsonContent.activities.map((activity, index) => ({
            ...activity,
            sectionTitle: activity.title || `Section ${index + 1}`,
            questions: activity.questions || []
          }))
        } else if (jsonContent) {
          examPrepSections.value = [{
            ...jsonContent,
            sectionTitle: jsonContent.section || jsonContent.title || 'Section 1',
            questions: jsonContent.questions || []
          }]
        } else {
          examPrepSections.value = []
        }
      } catch (e) {
        console.error('Error parsing exam prep content:', e)
        examPrepSections.value = []
      }

      examPrepSections.value.forEach((_, index) => {
        expandedSections.value[index] = false
      })

      await nextTick()
      const firstGroupKey = Object.keys(groupedSections.value)[0]
      if (firstGroupKey) {
        expandedGroups.value[firstGroupKey] = true
      }
    } else {
      examPrep.value = null
    }
  } catch (error) {
    console.error('Error fetching exam prep:', error)
    examPrep.value = null
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  const path = route.path || ''
  // Role-scoped learn routes take priority over the user_type — a student
  // genuinely landing on /admin/... would mean we're previewing as admin.
  if (path.startsWith('/student/')) {
    router.push('/student/exam-prep')
    return
  }
  if (path.startsWith('/tutor/')) {
    router.push('/tutor/exam-prep')
    return
  }
  if (path.startsWith('/admin/')) {
    router.push('/admin/exam-prep')
    return
  }
  // Shared preview route (/exam-preps/preview/...) — pick destination by the
  // logged-in user's role so students aren't bounced to /admin/exam-prep.
  const t = auth.user?.user_type
  if (t === 'student') router.push('/student/exam-prep')
  else if (t === 'tutor') router.push('/tutor/exam-prep')
  else if (t === 'admin' || t === 'superadmin' || t === 'super_admin') router.push('/admin/exam-prep')
  else router.push('/')
}

const toggleSection = (index) => {
  expandedSections.value[index] = !expandedSections.value[index]
}

const toggleGroup = (groupKey) => {
  expandedGroups.value[groupKey] = !expandedGroups.value[groupKey]
}

const toggleTextSection = (index) => {
  expandedTextSections.value[index] = !expandedTextSections.value[index]
}

const toggleWordBank = (index) => {
  expandedWordBanks.value[index] = !expandedWordBanks.value[index]
}

const getWordPairs = (section) => {
  if (section.wordBank && Array.isArray(section.wordBank) && section.wordBank.length > 0) {
    return section.wordBank.map(item => ({
      word: item.english || item.word || item.term || '',
      translation: item.french || item.translation || item.definition || ''
    })).filter(pair => pair.word && pair.translation)
  }
  return []
}

const getCorrectAnswerIndex = (sectionIndex, qIndex, questions) => {
  if (!questions || !questions[qIndex]) return -1
  const question = questions[qIndex]
  const ans = question.answer !== undefined ? question.answer :
             (question.correctAnswer !== undefined ? question.correctAnswer :
             (question.correct_answer !== undefined ? question.correct_answer : null))
  if (ans !== null && typeof ans === 'number') return ans
  if (typeof ans === 'string' && question.options) {
    return question.options.findIndex(opt => {
      const optText = typeof opt === 'object' ? (opt.text || opt.option || '') : opt
      return optText === ans
    })
  }
  return -1
}

const isOptionSelected = (sectionIndex, qIndex, oIndex) => {
  return userAnswers.value[`${sectionIndex}-${qIndex}`] === oIndex
}

const isOptionCorrect = (sectionIndex, qIndex, oIndex, questions) => {
  return getCorrectAnswerIndex(sectionIndex, qIndex, questions) === oIndex
}

const handleAnswerSelect = (sectionIndex, qIndex) => {
  if (isTcfScoringCategory.value) {
    return
  }
  const key = `${sectionIndex}-${qIndex}`
  answeredQuestions.value[key] = { selectedAnswer: String(userAnswers.value[key]), isCorrect: false }
}

const getOptionClass = (sectionIndex, qIndex, oIndex, questions) => {
  const isAnswered = !!answeredQuestions.value[`${sectionIndex}-${qIndex}`] || !!sectionResults.value[sectionIndex]
  const isSelected = isOptionSelected(sectionIndex, qIndex, oIndex)
  const isCorrect = isOptionCorrect(sectionIndex, qIndex, oIndex, questions)

  if (!isAnswered) {
    return isSelected ? 'border-[#0055A4] bg-[#0055A4]/5' : 'border-gray-100 bg-white shadow-sm'
  }
  if (isCorrect) {
    return isSelected ? 'border-green-500 bg-green-50' : 'border-green-500/30 bg-green-50/10'
  }
  if (isSelected) return 'border-red-500 bg-red-50'
  return 'border-gray-50 bg-gray-50/30 opacity-60'
}

const getRadioClass = (sectionIndex, qIndex, oIndex, questions) => {
  const isAnswered = !!answeredQuestions.value[`${sectionIndex}-${qIndex}`] || !!sectionResults.value[sectionIndex]
  const isSelected = isOptionSelected(sectionIndex, qIndex, oIndex)
  const isCorrect = isOptionCorrect(sectionIndex, qIndex, oIndex, questions)

  if (!isAnswered) {
    return isSelected ? 'border-[#0055A4] bg-[#0055A4]' : 'border-gray-300 bg-white'
  }
  if (isCorrect) return 'border-green-500 bg-green-50'
  if (isSelected) return 'border-red-500 bg-red-50'
  return 'border-gray-200 bg-gray-50'
}

const clearAnswers = (index) => {
  const section = examPrepSections.value[index]
  const questions = section?.questions || []
  questions.forEach((_, qIndex) => {
    delete userAnswers.value[`${index}-${qIndex}`]
    delete answeredQuestions.value[`${index}-${qIndex}`]
  })
}

const finishSection = (index) => {
  const section = examPrepSections.value[index]
  const questions = section?.questions || []
  let correct = 0

  questions.forEach((question, qIndex) => {
    const userAnswer = userAnswers.value[`${index}-${qIndex}`]
    const correctAnswerValue = question.answer ?? question.correctAnswer ?? question.correct_answer ?? null
    if (correctAnswerValue !== null) {
      if (typeof correctAnswerValue === 'number') {
        if (userAnswer === correctAnswerValue) correct++
      } else {
        const userSelectedOption = question.options && question.options[userAnswer]
        const userText = typeof userSelectedOption === 'object' ? userSelectedOption.text : userSelectedOption
        if (userText === correctAnswerValue) correct++
      }
    }
  })

  const total = questions.length
  const percentage = total > 0 ? Math.round((correct / total) * 100) : 0
  sectionResults.value[index] = { correct, total, percentage }
}

const retrySection = (index) => {
  delete sectionResults.value[index]
  clearAnswers(index)
}

const allQuestionsAnswered = (sectionIndex) => {
  const section = examPrepSections.value[sectionIndex]
  const questions = section?.questions || []
  if (questions.length === 0) return false
  for (let qIndex = 0; qIndex < questions.length; qIndex++) {
    if (userAnswers.value[`${sectionIndex}-${qIndex}`] === undefined) return false
  }
  return true
}

const nextSection = (index) => {
  if (index < examPrepSections.value.length - 1) {
    const nextIndex = index + 1
    expandedSections.value[index] = false
    expandedSections.value[nextIndex] = true
    nextTick(() => {
      const el = document.getElementById(`section-${nextIndex}`)
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
    })
  }
}

const completedSections = computed(() => Object.keys(sectionResults.value).length)

const progressPercentage = computed(() => {
  if (examPrepSections.value.length === 0) return 0
  return Math.round((completedSections.value / examPrepSections.value.length) * 100)
})

// --- Persistence: save/restore state to backend (DB) per user × exam prep ---
let canPersist = false
let saveTimer = null

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')
  if (!token) return null
  return {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json',
  }
}

const apiURL = import.meta.env.VITE_API_URL || ''

const buildState = () => ({
  userAnswers: userAnswers.value,
  answeredQuestions: answeredQuestions.value,
  sectionResults: sectionResults.value,
  expandedSections: expandedSections.value,
  expandedGroups: expandedGroups.value,
  expandedTextSections: expandedTextSections.value,
  expandedWordBanks: expandedWordBanks.value,
  expandedEssais: expandedEssais.value,
  expandedScenarios: expandedScenarios.value,
  expandedCombinaisons: expandedCombinaisons.value,
  expandedCorrections: expandedCorrections.value,
  groupResults: groupResults.value,
  // Quiz-flow state persisted to the DB (NOT localStorage):
  markedForReview: markedForReview.value,
  currentIndex: currentIndex.value,
  selectedGroupKey: selectedGroupKey.value,
  timeRemaining: timeRemaining.value,
  showResults: showResults.value,
  reviewMode: reviewMode.value,
  groupTimers: groupTimers.value,
  writtenDone: writtenDone.value,
  partieDone: partieDone.value,
  savedAt: Date.now(),
})

const saveStateToServer = async () => {
  // Persist for ANY authenticated user (student / tutor / admin / superadmin).
  // Each role gets their own row in exam_prep_progress keyed by user_id, so
  // there's no cross-pollution.
  const headers = getAuthHeaders()
  if (!headers) {
    console.warn('[ExamPrep] Save skipped: no auth token in localStorage. Log in to enable progress persistence.')
    return
  }
  try {
    const res = await fetch(`${apiURL}/api/exam-preps/${examPrepId}/my-progress`, {
      method: 'POST',
      headers: { ...headers, 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ state: buildState() }),
    })
    if (!res.ok) {
      let body = ''
      try { body = await res.text() } catch (e) {}
      console.warn(`[ExamPrep] Save failed: HTTP ${res.status}`, body.slice(0, 200))
    }
  } catch (e) {
    console.warn('[ExamPrep] Save network error:', e?.message)
  }
}

const persistState = () => {
  if (!canPersist) return
  if (saveTimer) clearTimeout(saveTimer)
  saveTimer = setTimeout(saveStateToServer, 500)
}

const restoreState = async () => {
  // Restore for ANY authenticated user (see saveStateToServer comment).
  const headers = getAuthHeaders()
  if (!headers) {
    console.warn('[ExamPrep] Restore skipped: no auth token in localStorage.')
    return
  }
  try {
    const res = await fetch(`${apiURL}/api/exam-preps/${examPrepId}/my-progress`, { headers, credentials: 'include' })
    if (!res.ok) {
      let body = ''
      try { body = await res.text() } catch (e) {}
      console.warn(`[ExamPrep] Restore failed: HTTP ${res.status}`, body.slice(0, 200))
      return
    }
    const json = await res.json()
    const state = json?.data?.state
    if (!state) {
      console.info('[ExamPrep] Restore: no prior state on server (fresh start).')
      return
    }
    console.info('[ExamPrep] Restored state from server, savedAt:', state.savedAt)

    // PHP json-encodes empty associative arrays as `[]` rather than `{}`.
    // If we assign that to our reactive ref, subsequent string-keyed writes
    // (like userAnswers["0-0"] = 1) become non-enumerable array properties
    // that JSON.stringify silently drops — losing every answer on next save.
    // Normalize: any field that should be an object becomes one.
    const toObj = (v) => (Array.isArray(v) || v === null || typeof v !== 'object') ? {} : v

    if (state.userAnswers !== undefined) userAnswers.value = toObj(state.userAnswers)
    if (state.answeredQuestions !== undefined) answeredQuestions.value = toObj(state.answeredQuestions)
    if (state.sectionResults !== undefined) sectionResults.value = toObj(state.sectionResults)
    if (state.expandedSections !== undefined) expandedSections.value = toObj(state.expandedSections)
    if (state.expandedGroups !== undefined) expandedGroups.value = toObj(state.expandedGroups)
    if (state.expandedTextSections !== undefined) expandedTextSections.value = toObj(state.expandedTextSections)
    if (state.expandedWordBanks !== undefined) expandedWordBanks.value = toObj(state.expandedWordBanks)
    if (state.expandedEssais !== undefined) expandedEssais.value = toObj(state.expandedEssais)
    if (state.expandedScenarios !== undefined) expandedScenarios.value = toObj(state.expandedScenarios)
    if (state.expandedCombinaisons !== undefined) expandedCombinaisons.value = toObj(state.expandedCombinaisons)
    if (state.expandedCorrections !== undefined) expandedCorrections.value = toObj(state.expandedCorrections)
    if (state.groupResults !== undefined) {
      const migrated = {}
      const cat = examPrep.value?.exam_prep_category?.toLowerCase()
      for (const [key, gr] of Object.entries(toObj(state.groupResults))) {
        const totalEarned = gr.totalEarned ?? 0
        const tcfInfo = getTcfLevelInfo(totalEarned)
        migrated[key] = {
          ...gr,
          level: gr.level ?? tcfInfo.level,
          status: gr.status ?? tcfInfo.status,
          achieved: gr.achieved ?? tcfInfo.achieved,
          levelLabel: gr.levelLabel ?? getTcfLevelLabel(totalEarned),
          // Always recompute NCLC (in case rangess changed since last save).
          // Old `clbLabel`/`clbInfo` keys from earlier versions are ignored.
          nclcLabel: getNclcLabel(totalEarned, cat),
          nclcInfo: getNclcInfo(totalEarned, cat),
          nclcBars: computeNclcBars(totalEarned, cat),
          nclcNextGoal: computeNclcNextGoal(totalEarned, cat),
          levelBars: computeLevelBars(totalEarned),
          nextGoal: computeNextGoal(totalEarned),
        }
      }
      groupResults.value = migrated
    }

    // Restore quiz-flow state (saved to DB, not localStorage).
    if (state.markedForReview !== undefined) markedForReview.value = toObj(state.markedForReview)
    if (typeof state.selectedGroupKey === 'string' || state.selectedGroupKey === null) {
      // Only restore if the group still exists in current data
      if (state.selectedGroupKey === null || groupedSections.value[state.selectedGroupKey]) {
        selectedGroupKey.value = state.selectedGroupKey
      }
    }
    if (Number.isFinite(state.currentIndex) && state.currentIndex >= 0 && state.currentIndex < examPrepSections.value.length) {
      currentIndex.value = state.currentIndex
    }
    if (Number.isFinite(state.timeRemaining) && state.timeRemaining >= 0 && state.timeRemaining <= TEST_DURATION_SECONDS) {
      timeRemaining.value = state.timeRemaining
    }
    // (timeTakenSeconds is a computed value derived from groupTimers; not restored directly)
    if (state.groupTimers !== undefined) groupTimers.value = toObj(state.groupTimers)
    if (state.writtenDone !== undefined) writtenDone.value = toObj(state.writtenDone)
    if (state.partieDone !== undefined) partieDone.value = toObj(state.partieDone)
    if (typeof state.showResults === 'boolean') showResults.value = state.showResults
    if (typeof state.reviewMode === 'boolean') reviewMode.value = state.reviewMode

    // If we restored into an active test (not on results panel), resume the timer
    // from the persisted timeRemaining instead of starting fresh at 60:00.
    if (selectedGroupKey.value && !showResults.value && timeRemaining.value > 0) {
      stopTimer()
      startTimer()
    }
  } catch (e) {
    // ignore network/parse errors
  }
}

watch(
  [userAnswers, answeredQuestions, sectionResults, expandedSections, expandedGroups,
   expandedTextSections, expandedWordBanks, expandedEssais, expandedScenarios,
   expandedCombinaisons, expandedCorrections, groupResults,
   markedForReview, currentIndex, selectedGroupKey, showResults, reviewMode,
   groupTimers, writtenDone, partieDone],
  persistState,
  { deep: true }
)

// Persist the running timer too, but throttled — saving every second would
// hammer the server. Save once every 15s while a test is active.
let timerPersistTick = 0
watch(timeRemaining, () => {
  if (!canPersist || !selectedGroupKey.value || showResults.value) return
  timerPersistTick++
  if (timerPersistTick % 15 === 0) persistState()
})

// Preview routes (admin/tutor "Preview" button) should always start fresh:
// no saved-state restore, no progress persistence, no URL ?test= auto-jump.
// This way the preview always lands on the clean test selector grid regardless
// of what the previewing user solved earlier in their own role.
const isPreviewRoute = computed(() => (route.path || '').startsWith('/exam-preps/preview/'))

onMounted(async () => {
  settingsStore.fetchSettings()
  await fetchExamPrep()
  if (!isPreviewRoute.value) {
    await restoreState()
  }
  // Wait for reactivity to settle before enabling persistence so the
  // initialization writes don't clobber the just-restored state.
  await nextTick()
  await nextTick()
  canPersist = !isPreviewRoute.value
  // Reconcile URL ?test=... with the test restored from DB.
  // - URL test matches DB → already restored (timer resumed from saved value).
  // - URL test differs from DB → switch to URL's test (fresh timer).
  // - URL has no test but DB had one → silently sync URL to match.
  // Skip entirely for preview routes — admin/tutor should land on the selector.
  if (!isPreviewRoute.value) {
    const urlTest = route.query.test
    if (urlTest && groupedSections.value[urlTest]) {
      if (selectedGroupKey.value !== urlTest) {
        goToTest(urlTest, { fromUrl: true })
      }
    } else if (selectedGroupKey.value) {
      router.replace({ query: { ...route.query, test: selectedGroupKey.value } }).catch(() => {})
    }
  }
  // Otherwise: timer stays idle until the user actually picks a test.
})

onBeforeUnmount(() => {
  if (timerInterval) {
    clearInterval(timerInterval)
    timerInterval = null
  }
})
</script>

<style scoped>
* {
  transition: background-color 0.2s ease, border-color 0.2s ease;
}
</style>
