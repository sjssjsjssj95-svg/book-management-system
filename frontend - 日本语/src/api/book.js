import request from "@/utils/request"

export const getAllBooks = () => {
    return request.get('/book/find/all')
}

export const getFiveBooks = () => {
    return request.get('/book/find/five')
}

export const getBookInfo = (id) => {
    return request.get('/book/find/'+id)
}

export const getBooksByCategoty = (categoty_id) => {
    return request.get('/book/find/categoty/'+categoty_id)
}

export const getBooksByTitle = (type,name) => {
    return request.get('/book/find/'+type+'/'+name)
}
