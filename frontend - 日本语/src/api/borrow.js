import request from "@/utils/request"

//借书
export const borrowBook = (data, token) => {
  return request.post('/borrow/createBorrow',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}
//查看接环记录
//正常状态
export const getBorrowNorLog = () => {
  return request.get('/borrow/get/nor')
}
//过期
export const getBorrowOutLog = () => {
  return request.get('/borrow/get/out')
}
//丢失
export const getBorrowLostLog = () => {
  return request.get('/borrow/get/lost')
}

//丢失
export const getBackLog = () => {
  return request.get('/borrow/get/back')
}

//归还
export const backBook = (data, token) => {
  return request.post('/borrow/back',data,{
    headers: {
      Authorization : `Bearer ${token}`
    }
  })
}