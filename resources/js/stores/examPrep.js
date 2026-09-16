import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useExamPrepStore = defineStore('examPrep', () => {
  const examPreps = ref([])
  const currentExamPrep = ref(null)
  const loading = ref(false)

  const fetchExamPreps = async (page = 1) => {
    loading.value = true
    try {
      const response = await axios.get(`/api/student/browse-exam-preps?page=${page}`)
      if (response.data.success && response.data.data) {
        const data = response.data.data.data || response.data.data
        examPreps.value = data.map(examPrep => ({
          id: examPrep.id,
          title: examPrep.exam_prep_title || examPrep.title || 'Untitled Exam Prep',
          description: examPrep.exam_prep_description || examPrep.description || '',
          image_url: examPrep.exam_prep_image ? `/storage/${examPrep.exam_prep_image.replace(/\/+$/, '')}` : examPrep.image_url || null,
          level: examPrep.exam_prep_level || examPrep.level || 'Beginner',
          category: examPrep.exam_prep_category || examPrep.category || '',
          language: examPrep.exam_prep_language || examPrep.language || 'French',
          instructor_name: examPrep.instructor_name || 'TBD'
        }))
      }
      return response.data
    } catch (error) {
      console.error('Error fetching exam preps:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchEnrolledExamPreps = async () => {
    loading.value = true
    try {
      const response = await axios.get('/api/student/exam-preps')
      if (response.data.success && response.data.data) {
        let enrollments = []
        if (response.data.data.data) {
          enrollments = response.data.data.data
        } else if (Array.isArray(response.data.data)) {
          enrollments = response.data.data
        } else {
          enrollments = [response.data.data]
        }

        examPreps.value = enrollments.map(enrollment => {
          const examPrep = enrollment.examPrep || enrollment
          return {
            id: examPrep.id,
            title: examPrep.exam_prep_title || examPrep.title || 'Untitled Exam Prep',
            description: examPrep.exam_prep_description || examPrep.description || '',
            image_url: examPrep.exam_prep_image ? `/storage/${examPrep.exam_prep_image.replace(/\/+$/, '')}` : examPrep.image_url || null,
            level: examPrep.exam_prep_level || examPrep.level || 'Beginner',
            instructor_name: examPrep.instructor_name || 'TBD',
            category: examPrep.exam_prep_category || examPrep.category,
            language: examPrep.exam_prep_language || examPrep.language,
            enrollment_id: enrollment.id,
            enrollment_status: enrollment.status,
            enrollment_date: enrollment.created_at || enrollment.enrollment_date
          }
        })
      } else {
        examPreps.value = []
      }
      return response.data
    } catch (error) {
      console.error('Error fetching enrolled exam preps:', error)
      examPreps.value = []
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchExamPrepDetail = async (examPrepId) => {
    loading.value = true
    try {
      const response = await axios.get(`/api/student/exam-preps/${examPrepId}`)
      if (response.data.success && response.data.data) {
        const raw = response.data.data.examPrep || response.data.data
        currentExamPrep.value = {
          id: raw.id,
          title: raw.exam_prep_title || raw.title || 'Untitled Exam Prep',
          description: raw.exam_prep_description || raw.description || '',
          image_url: raw.exam_prep_image ? `/storage/${raw.exam_prep_image.replace(/\/+$/, '')}` : raw.image_url || null,
          level: raw.exam_prep_level || raw.level || 'Beginner',
          category: raw.exam_prep_category || raw.category || '',
          language: raw.exam_prep_language || raw.language || 'French',
          instructor_name: raw.instructor_name || 'TBD',
          exam_prep_json_content: raw.exam_prep_json_content,
          exam_prep_is_active: raw.exam_prep_is_active
        }
      }
      return response.data
    } catch (error) {
      console.error('Error fetching exam prep detail:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const enrollExamPrep = async (examPrepId, classTypeId = null) => {
    try {
      const response = await axios.post('/api/student/exam-prep-enroll', {
        exam_prep_id: examPrepId,
        class_type_id: classTypeId
      })
      return response.data
    } catch (error) {
      console.error('Error enrolling exam prep:', error)
      throw error
    }
  }

  const searchExamPreps = async (query, category = null) => {
    loading.value = true
    try {
      const response = await axios.post('/api/student/search-exam-preps', {
        query,
        category
      })
      examPreps.value = response.data.data.data
      return response.data
    } catch (error) {
      console.error('Error searching exam preps:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  return {
    examPreps,
    currentExamPrep,
    loading,
    fetchExamPreps,
    fetchEnrolledExamPreps,
    fetchExamPrepDetail,
    enrollExamPrep,
    searchExamPreps
  }
})
