import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { reportDebug } from '../utils/errorReporter'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('../pages/auth/Login.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../pages/auth/Register.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: () => import('../pages/auth/ForgotPassword.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import('../pages/auth/ResetPassword.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/books',
    name: 'Books',
    component: () => import('../pages/Books.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/courses/preview/:id/:slug',
    name: 'CoursePreview',
    component: () => import('../pages/CourseView.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/exam-preps/preview/:id/:slug',
    name: 'ExamPrepPreview',
    component: () => import('../pages/ExamPrepView.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/student/courses/:id/:slug',
    name: 'StudentCourseLearn',
    component: () => import('../pages/student/Learn.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/student/exam-preps/:id/:slug',
    name: 'StudentExamPrepLearn',
    component: () => import('../pages/ExamPrepView.vue'),
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/payment/checkout',
    name: 'PaymentCheckout',
    component: () => import('../pages/PaymentCheckout.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/payment/success',
    name: 'PaymentSuccess',
    component: () => import('../pages/PaymentSuccess.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/student/maintenance',
    name: 'StudentMaintenance',
    component: () => import('../pages/student/Maintenance.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/student',
    name: 'StudentLayout',
    component: () => import('../layouts/StudentLayout.vue'),
    redirect: '/student/dashboard',
    meta: { requiresAuth: true, role: 'student' },
    children: [
      {
        path: 'dashboard',
        name: 'StudentDashboard',
        component: () => import('../pages/student/Dashboard.vue')
      },
      {
        path: 'courses',
        name: 'StudentCourses',
        component: () => import('../pages/student/Courses.vue')
      },
      {
        path: 'browse-courses',
        name: 'BrowseCourses',
        component: () => import('../pages/student/BrowseCourses.vue')
      },
      {
        path: 'courses/:id',
        name: 'CourseDetail',
        component: () => import('../pages/student/CourseDetail.vue')
      },
      {
        path: 'progress',
        name: 'Progress',
        component: () => import('../pages/student/Progress.vue')
      },
      {
        path: 'subscription',
        name: 'StudentSubscription',
        component: () => import('../pages/student/Subscription.vue')
      },
      {
        path: 'account',
        name: 'StudentAccount',
        component: () => import('../pages/student/Account.vue')
      },
      {
        path: 'homework',
        name: 'StudentHomework',
        component: () => import('../pages/student/Homework.vue')
      },
      {
        path: 'exam-prep',
        name: 'StudentExamPrep',
        component: () => import('../pages/student/ExamPrep.vue')
      },
      {
        path: 'browse-exam-preps',
        name: 'BrowseExamPrep',
        component: () => import('../pages/student/BrowseExamPrep.vue')
      },
      {
        path: 'exam-preps/:id',
        name: 'ExamPrepDetail',
        component: () => import('../pages/student/ExamPrepDetail.vue')
      },
    ]
  },
  {
    path: '/admin',
    name: 'AdminLayout',
    component: () => import('../layouts/AdminLayout.vue'),
    redirect: '/admin/dashboard',
    meta: { requiresAuth: true, role: ['admin', 'superadmin'] },
    children: [
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: () => import('../pages/admin/Dashboard.vue')
      },
      {
        path: 'courses',
        name: 'AdminCourses',
        component: () => import('../pages/admin/Courses.vue')
      },
      {
        path: 'exam-prep',
        name: 'AdminExamPrep',
        component: () => import('../pages/admin/ExamPrep.vue')
      },
      {
        path: 'books',
        name: 'AdminBooks',
        component: () => import('../pages/admin/Books.vue')
      },
      {
        path: 'students',
        name: 'AdminStudents',
        component: () => import('../pages/admin/Students.vue')
      },
      {
        path: 'tutors',
        name: 'AdminTutors',
        component: () => import('../pages/admin/Tutors.vue')
      },
      {
        path: 'enrollments',
        name: 'AdminEnrollments',
        component: () => import('../pages/admin/Enrollments.vue')
      },
      {
        path: 'coupons',
        name: 'AdminCoupons',
        component: () => import('../pages/admin/Coupons.vue')
      },
      {
        path: 'pages',
        name: 'AdminPages',
        component: () => import('../pages/admin/Pages.vue')
      },
      {
        path: 'class-types',
        name: 'AdminClassTypes',
        component: () => import('../pages/admin/ClassTypes.vue')
      },
      {
        path: 'manage-users',
        name: 'AdminManageUsers',
        component: () => import('../pages/admin/ManageUsers.vue')
      },
      {
        path: 'settings',
        name: 'AdminSettings',
        component: () => import('../pages/admin/Settings.vue')
      },
      {
        path: 'cache',
        name: 'AdminCache',
        component: () => import('../pages/admin/Cache.vue')
      },
      {
        path: 'attendance',
        name: 'AdminAttendance',
        component: () => import('../pages/admin/Attendance.vue')
      }
    ]
  },
  {
    path: '/tutor',
    name: 'TutorLayout',
    component: () => import('../layouts/TutorLayout.vue'),
    redirect: '/tutor/dashboard',
    meta: { requiresAuth: true, role: 'tutor' },
    children: [
      {
        path: 'dashboard',
        name: 'TutorDashboard',
        component: () => import('../pages/tutor/Dashboard.vue')
      },
      {
        path: 'courses',
        name: 'TutorCourses',
        component: () => import('../pages/tutor/Courses.vue')
      },
      {
        path: 'courses/:id/:slug',
        name: 'TutorCourseDetail',
        component: () => import('../pages/tutor/CourseDetail.vue')
      },
      {
        path: 'exam-prep',
        name: 'TutorExamPrep',
        component: () => import('../pages/tutor/ExamPrep.vue')
      },
      {
        path: 'exam-preps/:id/:slug',
        name: 'TutorExamPrepDetail',
        component: () => import('../pages/tutor/ExamPrepDetail.vue')
      },
      {
        path: 'students',
        name: 'TutorStudents',
        component: () => import('../pages/tutor/Students.vue')
      },
      {
        path: 'account',
        name: 'TutorAccount',
        component: () => import('../pages/tutor/Account.vue')
      },
      {
        path: 'homework',
        name: 'TutorHomework',
        component: () => import('../pages/tutor/Homework.vue')
      },
      {
        path: 'material',
        name: 'TutorMaterial',
        component: () => import('../pages/tutor/Material.vue')
      },
      {
        path: 'pay',
        name: 'TutorPay',
        component: () => import('../pages/tutor/Pay.vue')
      }
    ]
  },
  {
    path: '/demo',
    name: 'DemoLayout',
    component: () => import('../layouts/DemoLayout.vue'),
    redirect: '/demo/dashboard',
    meta: { requiresAuth: false },
    children: [
      {
        path: 'dashboard',
        name: 'DemoDashboard',
        component: () => import('../pages/demo/Dashboard.vue')
      },
      {
        path: 'courses',
        name: 'DemoCourses',
        component: () => import('../pages/demo/Courses.vue')
      },
      {
        path: 'courses/:id',
        name: 'DemoCourseDetail',
        component: () => import('../pages/demo/CourseDetail.vue')
      },
      {
        path: 'exam-prep',
        name: 'DemoExamPrep',
        component: () => import('../pages/demo/ExamPrep.vue')
      },
      {
        path: 'exam-prep/:id',
        name: 'DemoExamPrepDetail',
        component: () => import('../pages/demo/ExamPrepDetail.vue')
      },
      {
        path: 'homework',
        name: 'DemoHomework',
        component: () => import('../pages/demo/Homework.vue')
      },
      {
        path: 'account',
        name: 'DemoAccount',
        component: () => import('../pages/demo/Account.vue')
      }
    ]
  },
  // Public Page Route (catch-all for custom pages)
  {
    path: '/:slug',
    name: 'PublicPage',
    component: () => import('../pages/PublicPage.vue'),
    meta: { requiresAuth: false }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // Add trailing slash to all URLs if not present
  if (!to.path.endsWith('/')) {
    const pathWithSlash = to.path + '/'
    return next({
      path: pathWithSlash,
      query: to.query,
      hash: to.hash,
      replace: true
    })
  }

  // DEBUG: trace navigation into the admin exam-prep page (blank-page bug).
  const isExamPrepNav = to.path.includes('/admin/exam-prep')
  if (isExamPrepNav) {
    reportDebug('router guard: entering /admin/exam-prep', {
      step: 'guard-enter',
      to: to.path,
      from: from.path,
      hasToken: !!auth.token,
      hasUser: !!auth.user,
      userType: auth.user?.user_type ?? null,
      isAuthenticated: auth.isAuthenticated,
    })
  }

  // Load user data if token exists but user is not loaded
  if (auth.token && !auth.user) {
    try {
      await auth.getCurrentUser()
    } catch (error) {
      // Token is invalid, clear it
      console.error('Failed to load user in router guard:', error)
      if (isExamPrepNav) {
        reportDebug('router guard: getCurrentUser FAILED', {
          step: 'guard-getuser-error',
          status: error.response?.status,
          message: error.response?.data?.message || error.message,
        })
      }
    }
  }

  // If authenticated user visits landing, redirect to dashboard
  if (to.path === '/' && auth.isAuthenticated && auth.user) {
    // Redirect based on user type
    if (auth.user.user_type === 'admin' || auth.user.user_type === 'super_admin') return next('/admin/dashboard/')
    if (auth.user.user_type === 'tutor') return next('/tutor/dashboard/')
    return next('/student/dashboard/')
  }

  // Signed-in users get the real portal, not the demo.
  if (to.path.startsWith('/demo') && auth.isAuthenticated && auth.user) {
    if (auth.user.user_type === 'admin' || auth.user.user_type === 'super_admin') return next('/admin/dashboard/')
    if (auth.user.user_type === 'tutor') return next('/tutor/dashboard/')
    return next('/student/dashboard/')
  }

  // If protected route, check authentication
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    if (isExamPrepNav) reportDebug('router guard: NOT authenticated -> redirect /login/', { step: 'guard-redirect-login' })
    return next('/login/')
  }

  // Check role-based access (only if role is specified and user is authenticated)
  if (to.meta.requiresAuth && auth.isAuthenticated && to.meta.role && auth.user?.user_type) {
    const userRole = auth.user.user_type
    // Normalize the allowed roles to handle both 'superadmin' and 'super_admin'
    const normalizedAllowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role]
    const normalizedUserRole = userRole === 'super_admin' ? 'superadmin' : userRole

    if (!normalizedAllowedRoles.includes(normalizedUserRole)) {
      if (isExamPrepNav) {
        reportDebug('router guard: role NOT allowed -> redirecting away', {
          step: 'guard-redirect-role',
          userRole,
          normalizedUserRole,
          allowed: normalizedAllowedRoles,
        })
      }
      // Redirect to appropriate dashboard based on user role
      if (userRole === 'admin' || userRole === 'super_admin') return next('/admin/dashboard/')
      if (userRole === 'tutor') return next('/tutor/dashboard/')
      return next('/student/dashboard/')
    }
  }

  if (isExamPrepNav) reportDebug('router guard: passed all checks -> allowing navigation', { step: 'guard-allow' })

  // If unauthenticated user tries to access login/register, allow it
  if (!to.meta.requiresAuth && auth.isAuthenticated && auth.user && (to.path === '/login/' || to.path === '/register/')) {
    // Redirect authenticated users away from auth pages to dashboard
    if (auth.user.user_type === 'admin' || auth.user.user_type === 'super_admin') return next('/admin/dashboard/')
    if (auth.user.user_type === 'tutor') return next('/tutor/dashboard/')
    return next('/student/dashboard/')
  }

  next()
})

export default router
