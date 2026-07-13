import request from "@/utils/rootRequest";

export const login = (data) => {
    return request.post('/root/login',data)
}

//查询root是否登录
export const loginIO = (token) => {
    return request.post('/root/loginIO',token)
}

//查询图书总数
export const tableBooks = (token) => {
    return request.get('/root/table/books',token)
}
//查询user总数
export const getUserNumber = (token) => {
    return request.get('/root/table/users',token)
}
//查询borrow总数
export const getBorrowNumber = (token) => {
    return request.get('/root/table/borrow',token)
}

//退出登录
export const logout = (token) => {
  return request.post('/root/logout',token)
}

//获取root数据
export const getRootInfo = (token) => {
  return request.get('/root/get/info',token)
}

//获取books数据
export const getAllBooks = (token) => {
  return request.get('/root/get/books',token)
}

//删除book
export const deleteBook = (token) => {
  return request.post('/root/book/delete',token)
}
//修改book
export const updataBook = (data,token) => {
  return request.post('/root/book/updata',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//添加Info
export const addInfo = (data , token) => {
  return request.post('/root/info/add',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//添加Info
export const deleteInfo = (data , token) => {
  return request.post('/root/info/delete',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}


//user
//获取所有user
export const getAllUser = (token) => {
  return request.get('/root/user/all',token)
}
//封禁user/off
export const banUser = (data , token) => {
  return request.post('/root/user/ban',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}
//解禁user
export const banOffUser = (data , token) => {
  return request.post('/root/user/ban/off',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}
//删除user
export const deleteUser = (data , token) => {
  return request.post('/root/user/delete',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}

//借阅
//查询所有借阅，根据status分类
export const getBorrowAll = (data , token) => {
  return request.post('/root/borrow/all',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}
//管理员确认归还
export const rootConfirm = (data , token) => {
  return request.post('/root/borrow/confirm',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}


//找回密码
//发送email
export const findPasswordSned = (email) => {
  return request.post('/root/reset/password/send',{
    email: email
  })
}

//重置密码
export const findPasswrd = (data) => {
  return request.post('/root/reset/password',data)
}