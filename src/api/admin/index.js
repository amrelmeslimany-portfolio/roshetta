import { request } from "../../utils/reusedFunctions";

export const viewRoshettaNumbers = (token) => {
  // auth header with bearer token
  const endpoint = "/admins/view_number_all";
  const options = { headers: { Authorization: `Bearer ${token}` } };
  return request(endpoint, options);
};
export const viewMessage = (type = "", status = "", token) => {
  // auth header with bearer token
  const endpoint = `/admins/view_message?type=${type}&status=${status}`;
  const options = { headers: { Authorization: `Bearer ${token}` } };
  return request(endpoint, options);
};

export const replyMessageUser = (id = "", formData, token) => {
  // auth header with bearer token
  const endpoint = `/admins/reply_message_user/${id}`;
  const options = {
    method: "POST",
    body: formData,
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const viewActivation = (type = "", filter = "", status = "0", token) => {
  // auth header with bearer token
  const endpoint = `/admins/view_activation?filter=${filter}&type=${type}&status=${status}`;
  const options = { headers: { Authorization: `Bearer ${token}` } };
  return request(endpoint, options);
};

export const activateUser = (
  type = "",
  activationId = "",
  status = "",
  token
) => {
  // auth header with bearer token
  const endpoint = `/admins/activation_user_place?type=${type}&activation_id=${activationId}&status=${status}`;
  const options = {
    method: "POST",
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const getUsers = (type = "", filter = "", token) => {
  // auth header with bearer token
  const endpoint = `/admins/view_users?type=${type}&filter=${filter}`;
  const options = { headers: { Authorization: `Bearer ${token}` } };
  return request(endpoint, options);
};

export const viewUserDetails = async (type = "", id = "", token) => {
  // auth header with bearer token
  const endpoint = `/admins/view_users_details/${id}?type=${type}`;
  const options = { headers: { Authorization: `Bearer ${token}` } };
  return request(endpoint, options);
};

export const deleteUser = (type = "", id = "", token) => {
  const endpoint = `/admins/remove_user_place/${id}?type=${type}`;
  const options = {
    method: "POST",
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const editProfileDetails = (type = "", id = "", formData, token) => {
  // auth header with bearer token
  const endpoint = `/admins/edit_profile_user/${id}?type=${type}`;
  const options = {
    method: "POST",
    body: formData,
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const editProfilePlaceDetails = (
  type = "",
  id = "",
  formData,
  token
) => {
  // auth header with bearer token
  const endpoint = `/admins/edit_profile_place/${id}?type=${type}`;
  const options = {
    method: "POST",
    body: formData,
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const editPasswordDetails = (type = "", id = "", formData, token) => {
  // auth header with bearer token
  const endpoint = `/admins/edit_password_user/${id}?type=${type}`;
  const options = {
    method: "POST",
    body: formData,
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};

export const editEmailOrSSdDetails = (
  editType,
  type = "",
  id = "",
  formData,
  token
) => {
  // auth header with bearer token
  const endpoint = `/admins/edit_email_ssd_user/${id}?type=${type}&type_user_q=${editType}`;
  const options = {
    method: "POST",
    body: formData,
    headers: { Authorization: `Bearer ${token}` },
  };
  return request(endpoint, options);
};
