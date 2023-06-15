import { API_SERVER } from "../constants/server";

export const request = async (endpoint, options) => {
  const response = await fetch(`${API_SERVER}${endpoint}`, options);
  if (!response.ok) {
    throw new Error(`مشكله من السيرفر`);
  }
  return await response.json();
};

export const isRequestSuccess = (status) => status >= 200 && status <= 299;

export const objectToString = (object) => {
  return Object.values(object)
    .filter((value) => value.trim() != "")
    .join(" - ");
};

export const errorToString = (error) => {
  if (typeof error == "string") return error;
  return objectToString(error);
};

export const initalWindowScroll = () => {
  window.scrollTo({
    top: 0,
    left: 0,
    behavior: "smooth",
  });
};
