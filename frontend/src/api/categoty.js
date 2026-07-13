import request from "@/utils/request";

export const getAllCategoty = () => {
    return request.get('/categoty/find/all')
}