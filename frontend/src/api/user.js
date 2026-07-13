import request from "@/utils/request";

export const sendRegisterCode = (email) => {
  return request.post('/user/register/send', {
    email: email
  })
}

export const register = (data) => {
  return request.post('/user/register',data)
}

export const passwordLogin = (data) => {
  return request.post('/user/login/password',data)
}

//验证user是否登录
export const loginIO = (token) => {
  return request.post('/user/loginIO',token)
}

//退出登录
export const logout = (token) => {
  return request.post('/user/logout',token)
}

//找回账户
export const findUserName = (email) => {
  return request.post('/user/find/name',{
    email: email
  })
}

//找回密码
//发送email
export const findPasswordSned = (email) => {
  return request.post('/user/find/password/send',{
    email: email
  })
}

//找回账户
export const findPasswrd = (data) => {
  return request.post('/user/find/password',data)
}

//查找userinfo
export const getUserInfo = (token) => {
  return request.post('/user/get/info',token)
}

//修改username
export const updateUserName = ( data , token ) => {
  return request.post('/user/update/username',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//修改头像
export const uploadAvatar = ( data , token ) => {
  return request.post('/user/update/img',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//修改userPassword
export const updateUserPassword = ( data , token ) => {
  return request.post('/user/update/password',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//修改useremail
export const resetEmailSendEmail = ( data , token ) => {
  return request.post('/user/update/email/send',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

export const resetEmail = ( data , token ) => {
  return request.post('/user/update/email',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}