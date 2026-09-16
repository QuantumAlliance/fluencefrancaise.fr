import { computed } from 'vue'

/**
 * Get user initials from name
 * @param {string|object} user - User object with name/email or string
 * @returns {string} Initials (1-2 letters)
 */
export function useAvatar(user) {
  const getInitials = (userData) => {
    if (!userData) return 'U'
    
    let name = ''
    
    // If it's a string, use it directly
    if (typeof userData === 'string') {
      name = userData.trim()
    } 
    // If it's an object, try to get name from various fields
    else if (typeof userData === 'object') {
      name = userData.name || 
             `${userData.first_name || ''} ${userData.last_name || ''}`.trim() ||
             userData.first_name ||
             userData.last_name ||
             userData.email ||
             userData.username ||
             ''
    }
    
    if (!name) return 'U'
    
    // Remove email domain if it's an email
    name = name.split('@')[0]
    
    // Split by spaces and get first letters
    const parts = name.trim().split(/\s+/).filter(p => p.length > 0)
    
    if (parts.length === 0) return 'U'
    
    // If only one part, take first letter
    if (parts.length === 1) {
      return parts[0].charAt(0).toUpperCase()
    }
    
    // If multiple parts, take first letter of first and last part
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
  }
  
  const initials = computed(() => {
    return getInitials(user)
  })
  
  const getInitialsColor = (initials) => {
    // Generate a consistent color based on initials
    const colors = [
      'bg-[#0055A4]',
      'bg-blue-500',
      'bg-green-500',
      'bg-purple-500',
      'bg-pink-500',
      'bg-indigo-500',
      'bg-red-500',
      'bg-yellow-500',
      'bg-teal-500',
      'bg-orange-500',
    ]
    
    const index = initials.charCodeAt(0) % colors.length
    return colors[index]
  }
  
  const avatarColor = computed(() => {
    return getInitialsColor(initials.value)
  })
  
  return {
    initials,
    avatarColor,
    getInitials,
    getInitialsColor
  }
}

