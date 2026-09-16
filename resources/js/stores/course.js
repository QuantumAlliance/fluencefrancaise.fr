import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useCourseStore = defineStore('course', () => {
  const courses = ref([])
  const currentCourse = ref(null)
  const loading = ref(false)

  const fetchCourses = async (page = 1) => {
    loading.value = true
    try {
      const response = await axios.get(`/api/student/browse-courses?page=${page}`)
      // Handle paginated response
      if (response.data.success && response.data.data) {
        const coursesData = response.data.data.data || response.data.data
        courses.value = coursesData.map(course => ({
          id: course.id,
          title: course.course_title || course.title || 'Untitled Course',
          description: course.course_description || course.description || '',
          image_url: course.course_image ? `/storage/${course.course_image.replace(/\/+$/, '')}` : course.image_url || null,
          level: course.course_level || course.level || 'Beginner',
          category: course.course_category || course.category || '',
          language: course.course_language || course.language || 'French',
          instructor_name: course.instructor_name || 'TBD'
        }))
      }
      return response.data
    } catch (error) {
      console.error('Error fetching courses:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchEnrolledCourses = async () => {
    loading.value = true
    try {
      const response = await axios.get('/api/student/courses')
      // API returns enrollments with course data nested
      if (response.data.success && response.data.data) {
        // Handle paginated response - check if data has 'data' property (paginated) or is direct array
        let enrollments = []
        if (response.data.data.data) {
          // Paginated response
          enrollments = response.data.data.data
        } else if (Array.isArray(response.data.data)) {
          // Direct array
          enrollments = response.data.data
        } else {
          // Single object
          enrollments = [response.data.data]
        }
        
        // Extract course data from enrollments
        courses.value = enrollments.map(enrollment => {
          // Enrollment has nested 'course' object
          const course = enrollment.course || enrollment
          return {
            id: course.id,
            title: course.course_title || course.title || 'Untitled Course',
            description: course.course_description || course.description || '',
            image_url: course.course_image ? `/storage/${course.course_image.replace(/\/+$/, '')}` : course.image_url || null,
            level: course.course_level || course.level || 'Beginner',
            instructor_name: course.instructor_name || 'TBD',
            category: course.course_category || course.category,
            language: course.course_language || course.language,
            enrollment_id: enrollment.id,
            enrollment_status: enrollment.status,
            enrollment_date: enrollment.created_at || enrollment.enrollment_date
          }
        })
      } else {
        courses.value = []
      }
      return response.data
    } catch (error) {
      console.error('Error fetching enrolled courses:', error)
      courses.value = []
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchCourseDetail = async (courseId) => {
    loading.value = true
    try {
      const response = await axios.get(`/api/student/courses/${courseId}`)
      if (response.data.success && response.data.data) {
        const courseData = response.data.data
        currentCourse.value = {
          id: courseData.id,
          title: courseData.course_title || courseData.title || 'Untitled Course',
          description: courseData.course_description || courseData.description || '',
          image_url: courseData.course_image ? `/storage/${courseData.course_image.replace(/\/+$/, '')}` : courseData.image_url || null,
          level: courseData.course_level || courseData.level || 'Beginner',
          category: courseData.course_category || courseData.category || '',
          language: courseData.course_language || courseData.language || 'French',
          instructor_name: courseData.instructor_name || 'TBD',
          course_json_content: courseData.course_json_content,
          course_is_active: courseData.course_is_active
        }
      }
      return response.data
    } catch (error) {
      console.error('Error fetching course detail:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const enrollCourse = async (courseId, classTypeId = null) => {
    try {
      const response = await axios.post('/api/student/enroll', {
        course_id: courseId,
        class_type_id: classTypeId
      })
      return response.data
    } catch (error) {
      console.error('Error enrolling course:', error)
      throw error
    }
  }

  const searchCourses = async (query, category = null) => {
    loading.value = true
    try {
      const response = await axios.post('/api/student/search-courses', {
        query,
        category
      })
      courses.value = response.data.data.data
      return response.data
    } catch (error) {
      console.error('Error searching courses:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  return {
    courses,
    currentCourse,
    loading,
    fetchCourses,
    fetchEnrolledCourses,
    fetchCourseDetail,
    enrollCourse,
    searchCourses
  }
})
