export default [
  {
    path: '',
    name: 'register-phone',
    component: require('../components/auth/register-phone')
  },
  {
    path: 'captcha',
    name: 'register-captcha',
    component: require('../components/auth/register-captcha')
  },
  {
    path: 'verifyCode',
    name: 'register-reifyCode',
    component: require('../components/auth/register-verifyCode')
  },
  {
    path: 'info',
    name: 'register-info',
    component: require('../components/auth/register-info')
  }
]
