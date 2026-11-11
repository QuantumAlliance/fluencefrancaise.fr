<template>
  <footer id="contact" class="bg-[#002654] text-slate-300 relative">
    <!-- Signature Tricolor Bar -->
    <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">
        <!-- Brand & Description Column -->
        <div class="md:col-span-4">
          <div class="flex items-center gap-2.5 mb-5">
            <img src="/images/brand-mark.png" :alt="siteName" class="h-9 w-auto">
            <span style="font-family:'Urbanist',system-ui,sans-serif" class="text-2xl font-bold text-white tracking-tight">
              Fluence <span class="text-[#EF4135]">Française</span>
            </span>
          </div>
          <p class="text-sm leading-relaxed text-slate-300/90 max-w-sm">{{ description }}</p>
          <div class="flex gap-3 mt-6">
            <template v-for="(url, platform) in socialLinks" :key="platform">
              <a
                v-if="url && url !== '#'"
                :href="url"
                target="_blank"
                rel="noopener noreferrer"
                class="w-9 h-9 rounded-full flex items-center justify-center bg-white/10 hover:bg-[#EF4135] text-white transition"
              >
                <i :class="getIconClass(platform)"></i>
              </a>
            </template>
          </div>
        </div>

        <!-- Column 1: Explore -->
        <div class="md:col-span-2 md:col-start-6">
          <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">
            {{ isFr ? 'Explorer' : 'Explore' }}
          </h4>
          <ul class="space-y-3 text-sm">
            <li v-for="(link, index) in quickLinks" :key="index">
              <a :href="link.url" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">
                {{ link.text }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Column 2: Contact -->
        <div class="md:col-span-3">
          <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Contact</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-3">
              <i class="fas fa-envelope text-[#5993d0] w-4"></i>
              <a :href="`mailto:${contactEmail}`" class="hover:text-white transition">{{ contactEmail }}</a>
            </li>
            <li class="flex items-center gap-3">
              <i class="fas fa-phone text-[#5993d0] w-4"></i>
              <span>{{ contactPhone }}</span>
            </li>
            <li class="flex items-center gap-3">
              <i class="fab fa-whatsapp text-[#5993d0] w-4"></i>
              <span>{{ isFr ? 'WhatsApp disponible' : 'WhatsApp available' }}</span>
            </li>
          </ul>
        </div>

        <!-- Column 3: Exams -->
        <div class="md:col-span-2">
          <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">
            {{ isFr ? 'Examens' : 'Exams' }}
          </h4>
          <ul class="space-y-3 text-sm">
            <li v-for="(link, index) in examLinks" :key="index">
              <a :href="link.url" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">
                {{ link.text }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Independence Legal Disclaimer Line -->
      <div class="border-t border-white/10 pt-6 pb-6 text-xs text-slate-400 leading-relaxed max-w-4xl">
        <p>{{ independenceLine }}</p>
      </div>

      <!-- Copyright & Legal Links -->
      <div class="border-t border-white/10 pt-6 flex justify-between items-center flex-wrap gap-4 text-sm text-slate-400">
        <p>© {{ currentYear }} {{ siteName }}. {{ isFr ? 'Tous droits réservés.' : 'All rights reserved.' }}</p>
        <div class="flex items-center gap-4">
          <a v-for="(link, index) in legalLinks" :key="index" :href="link.url" class="hover:text-white transition">
            {{ link.text }}
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useSettingsStore } from '../stores/settings'

const settingsStore = useSettingsStore()

onMounted(() => {
  settingsStore.fetchSettings()
})

const siteName = computed(() => settingsStore.siteName || 'Fluence Française')
const currentYear = new Date().getFullYear()

// Language detection
const isFr = computed(() => {
  if (typeof window !== 'undefined') {
    return window.location.pathname.startsWith('/fr')
  }
  return false
})

const description = computed(() => {
  return isFr.value
    ? "Fluence Française : cours de français en ligne en direct avec un tuteur, du A1 au B2. Préparation au TCF IRN, TEF IRN, DELF et test fide, examen civique inclus, pour vos démarches en France et en Suisse."
    : "Fluence Française: live online French classes with a tutor, A1 to B2. Prep for the TCF IRN, TEF IRN, DELF and fide test, civic exam included, for applications in France and Switzerland."
})

const contactEmail = computed(() => {
  return settingsStore.footer?.contact?.email || settingsStore.general?.contact_email || 'contact@fluencefrancaise.fr'
})

const contactPhone = computed(() => {
  return settingsStore.footer?.contact?.phone || settingsStore.general?.contact_phone || '+33 1 23 45 67 89'
})

const socialLinks = computed(() => {
  return settingsStore.footer?.social || {
    facebook: '#',
    instagram: '#',
    twitter: '#',
    linkedin: '#'
  }
})

const getIconClass = (platform) => {
  const icons = {
    facebook: 'fab fa-facebook-f',
    twitter: 'fab fa-twitter',
    instagram: 'fab fa-instagram',
    linkedin: 'fab fa-linkedin-in'
  }
  return icons[platform] || 'fas fa-link'
}

const quickLinks = computed(() => {
  return [
    { text: isFr.value ? 'Accueil' : 'Home', url: isFr.value ? '/fr' : '/en' },
    { text: isFr.value ? 'Cours' : 'Courses', url: isFr.value ? '/fr/cours-de-francais-en-ligne' : '/en/online-french-classes' },
    { text: isFr.value ? 'Parrainage' : 'Referral', url: isFr.value ? '/fr/contact#parrainage' : '/en/contact#referral' },
    { text: 'FAQ', url: isFr.value ? '/fr#faq' : '/en#faq' }
  ]
})

const examLinks = computed(() => {
  return [
    { text: 'TCF IRN', url: isFr.value ? '/fr/tcf-irn' : '/en/tcf-irn' },
    { text: 'TEF IRN', url: isFr.value ? '/fr/tef-irn' : '/en/tef-irn' },
    { text: 'DELF', url: isFr.value ? '/fr/delf' : '/en/delf' },
    { text: isFr.value ? 'Test fide' : 'fide test', url: isFr.value ? '/fr/test-fide' : '/en/fide-test' },
    { text: isFr.value ? 'Examen civique' : 'Civic exam', url: isFr.value ? '/fr/examen-civique' : '/en/french-civic-exam' }
  ]
})

const legalLinks = computed(() => {
  return [
    { text: isFr.value ? 'Mentions légales' : 'Legal notice', url: '/new-policies#legal' },
    { text: isFr.value ? 'Politique de confidentialité' : 'Privacy policy', url: '/new-policies' }
  ]
})

const independenceLine = computed(() => {
  return isFr.value
    ? "Fluence Française est un organisme indépendant de préparation aux examens. Nous ne faisons pas passer le TCF, le TEF, le DELF, le DALF, le test fide ou l'examen civique, et nous ne sommes affiliés à aucun organisme certificateur."
    : "Fluence Française is an independent prep school. We don't administer the TCF, TEF, DELF, DALF, fide or civic exams, and we're not affiliated with any exam body."
})
</script>
