import request from "@/utils/request"

export const getAllInfo = () => {
    return request.get('/info/find/all')
}