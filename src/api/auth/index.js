import { request } from "../../utils/reusedFunctions";

export const login = (data) => {
  const options = {
    method: "POST",
    body: data,
  };
  return request("/users/login", options);
};

export const register = (data) => {
  const options = {
    method: "POST",
    body: data,
  };
  return request("/users/register", options);
};

export const logout = (token) => {
  const options = {
    method: "GET",
    headers: { Authorization: `Bearer ${token}` },
  };
  return request("/users/logout", options);
};
