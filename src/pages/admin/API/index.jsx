let getToken;
let token;

getToken = () => {
  let tokenData;
  if (JSON.parse(localStorage.getItem("userData"))) {
    tokenData = JSON.parse(localStorage.getItem("userData"));
  }
  token = tokenData.token;
};

export const getOrders = () => {
  return fetch("https://dummyjson.com/carts/1").then((res) => res.json());
};

export const getRevenue = () => {
  return fetch("https://dummyjson.com/carts").then((res) => res.json());
};

export const getInventory = () => {
  return fetch("https://dummyjson.com/products").then((res) => res.json());
};

export const getComments = () => {
  return fetch("https://dummyjson.com/comments").then((res) => res.json());
};
export const logOut = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token
  localStorage.clear("userData");

  return fetch(`http://localhost:80/roshetta/api/users/logout`, {
    headers,
  }).then((res) => res.json());
};

export const viewRoshettaNumbers = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(`http://localhost:80/roshetta/api/admins/view_number_all`, {
    headers,
  }).then((res) => res.json());
};
export const viewMessage = (type = "", status = "") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `http://localhost:80/roshetta/api/admins/view_message?type=${type}&status=${status}`,
    { headers }
  ).then((res) => res.json());
};

export const replyMessageUser = (id = "", formData) => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `http://localhost:80/roshetta/api/admins/reply_message_user/${id}`,
    { headers, method: "POST", body: formData }
  ).then((res) => res.json());
};

export const viewActivation = (type = "", filter = "", status = "0") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `http://localhost:80/roshetta/api/admins/view_activation?filter=${filter}&type=${type}&status=${status}`,
    { headers }
  ).then((res) => res.json());
};
export const activateUser = (type = "", activationId = "", status = "") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `http://localhost:80/roshetta/api/admins/activation_user_place?type=${type}&activation_id=${activationId}&status=${status}`,
    {
      method: "POST",
      headers,
    }
  ).then((res) => res.json());
};

export const getUsers = (type = "", filter = "") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `http://localhost:80/roshetta/api/admins/view_users?type=${type}&filter=${filter}`,
    { headers }
  ).then((res) => res.json());
};

export const viewUserDetails = async (type = "", id = "") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  const response = await fetch(
    `http://localhost:80/roshetta/api/admins/view_users_details/${id}?type=${type}`,
    { headers }
  );
  const data = await response.json();
  return data;

  // return fetch(
  //   `http://localhost:80/roshetta/api/admins/view_users_details/${id}?type=${type}`,
  //   { headers }
  // ).then((res) => res.json());
};

export const deleteUser = (type = "", id = "") => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `
    http://localhost:80/roshetta/api/admins/remove_user_place/${id}?type=${type}`,
    {
      method: "POST",
      headers,
    }
  ).then((res) => res.json());
};

export const editProfileDetails = (type = "", id = "", formData) => {
  http: getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `
    http://localhost:80/roshetta/api/admins/edit_profile_user/${id}?type=${type}`,
    {
      method: "POST",
      headers,
      body: formData,
    }
  ).then((res) => res.json());
};

export const editProfilePlaceDetails = (type = "", id = "", formData) => {
  //localhost:80/roshetta/api/admins/edit_profile_place/6?type=
  http: getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `
    http://localhost:80/roshetta/api/admins/edit_profile_place/${id}?type=${type}
    `,
    {
      method: "POST",
      headers,
      body: formData,
    }
  ).then((res) => res.json());
};

export const editPasswordDetails = (type = "", id = "", formData) => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    ` 
    http://localhost:80/roshetta/api/admins/edit_password_user/${id}?type=${type}`,
    {
      method: "POST",
      headers,
      body: formData,
    }
  ).then((res) => res.json());
};

export const editEmailOrSSdDetails = (
  editType,
  type = "",
  id = "",
  formData
) => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    `
    http://localhost:80/roshetta/api/admins/edit_email_ssd_user/${id}?type=${type}&type_user_q=${editType}`,
    {
      method: "POST",
      headers,
      body: formData,
    }
  ).then((res) => res.json());
};
