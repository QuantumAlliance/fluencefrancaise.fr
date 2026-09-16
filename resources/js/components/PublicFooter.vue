<template>
  <footer id="contact" class="bg-[#002654] text-slate-300 relative">
    <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-10">
        <!-- About Section -->
        <div class="md:col-span-5">
          <div class="flex items-center gap-2.5 mb-5">
            <template v-if="settingsStore.siteLogo">
              <span class="inline-flex bg-white rounded-2xl p-2"><img :src="logoUrl" :alt="settingsStore.siteName" class="h-8 w-auto"></span>
            </template>
            <template v-else>
              <span class="inline-flex h-[26px] w-[9px] rounded-sm overflow-hidden shadow"><i class="block flex-1" style="height:33.3%;background:#0055A4"></i></span>
              <span style="font-family:'Urbanist',system-ui,sans-serif" class="text-2xl font-bold text-white tracking-tight">Fluence<span class="text-[#EF4135]">.</span></span>
            </template>
          </div>
          <p class="text-sm leading-relaxed text-slate-300/90 max-w-md">{{ description }}</p>
          <div class="flex gap-3 mt-6">
            <template v-for="(url, platform) in socialLinks" :key="platform">
              <a
                v-if="url && url !== '#'"
                :href="url"
                target="_blank"
                class="w-9 h-9 rounded-full flex items-center justify-center bg-white/10 hover:bg-[#EF4135] text-white transition"
              >
                <i :class="getIconClass(platform)"></i>
              </a>
            </template>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="md:col-span-3">
          <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Explore</h4>
          <ul class="space-y-3 text-sm">
            <li v-for="(link, index) in quickLinks" :key="index">
              <a :href="link.url" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">{{ link.text }}</a>
            </li>
          </ul>
        </div>

        <!-- Contact Information -->
        <div class="md:col-span-4">
          <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Get in touch</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-3">
              <i class="fas fa-envelope text-[#5993d0] w-4"></i>
              <a :href="`mailto:${contactInfo.email}`" class="hover:text-white transition">{{ contactInfo.email }}</a>
            </li>
            <li class="flex items-center gap-3">
              <i class="fas fa-phone text-[#5993d0] w-4"></i> {{ contactInfo.phone }}
            </li>
            <li class="flex items-center gap-3">
              <i class="fas fa-map-marker-alt text-[#5993d0] w-4"></i> {{ contactInfo.address }}
            </li>
          </ul>
          <div class="mt-5 flex flex-wrap gap-2">
            <span v-for="exam in ['DELF','DALF','TCF','TEF']" :key="exam" class="text-[11px] font-bold tracking-wide px-3 py-1 rounded-full bg-white/10 text-white">{{ exam }}</span>
          </div>
        </div>
      </div>

      <div class="border-t border-white/10 pt-7 flex justify-between items-center flex-wrap gap-4">
        <p class="text-sm text-slate-400">{{ copyrightText }}</p>
        <div class="flex items-center gap-4 text-sm text-slate-400">
          <a v-for="(link, index) in legalLinks" :key="index" :href="link.url" class="hover:text-white transition">{{ link.text }}</a>
          <span class="hidden sm:inline text-slate-600">·</span>
          <span class="hidden sm:inline" v-html="creditText"></span>
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

const description = "Live online French classes that take you from A1 to B2 and into DELF, DALF, TCF and TEF exam readiness — structured, teacher-led, built for the life you're creating in France."

const logoUrl = computed(() => {
  return settingsStore.siteLogo
    ? `${import.meta.env.VITE_API_URL}/storage/${settingsStore.siteLogo}`
    : ''
})

const socialLinks = computed(() => {
  return settingsStore.footer?.social || {}
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
  return settingsStore.footer?.quickLinks?.length
    ? settingsStore.footer.quickLinks
    : [
        { text: 'Home', url: '/' },
        { text: 'Courses', url: '/our-courses' },
        { text: 'Pricing', url: '/#pricing' },
        { text: 'Student Portal', url: '/#portal' },
        { text: 'FAQ', url: '/#faq' },
        { text: 'Contact Us', url: '/contact-us' }
      ]
})

const legalLinks = computed(() => {
  return settingsStore.footer?.legalLinks?.length
    ? settingsStore.footer.legalLinks
    : [{ text: 'Privacy Policy', url: '/new-policies' }]
})

const contactInfo = computed(() => {
  return {
    email: settingsStore.footer?.contact?.email || 'contact@fluencefrancaise.com',
    phone: settingsStore.footer?.contact?.phone || '+33 1 23 45 67 89',
    address: settingsStore.footer?.contact?.address || 'Online · Based in France'
  }
})

const copyrightText = computed(() => {
  if (settingsStore.footer?.copyrightText) {
    return settingsStore.footer.copyrightText
      .replace('{year}', new Date().getFullYear())
      .replace('{siteName}', settingsStore.siteName)
  }
  return `© ${new Date().getFullYear()} ${settingsStore.siteName}. All rights reserved.`
})

const creditText = computed(() => {
  return settingsStore.footer?.creditText || 'Fait avec <i class="fas fa-heart text-[#EF4135]"></i> pour les apprenants du français'
})
</script>
