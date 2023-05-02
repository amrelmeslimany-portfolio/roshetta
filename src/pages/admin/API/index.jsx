let token;
const getToken = () => {
  let tokenData;
  if (JSON.parse(localStorage.getItem('userData'))) {
    tokenData = JSON.parse(localStorage.getItem('userData'));
  }
  token = tokenData.token;
};

export const getOrders = () => {
  return fetch('https://dummyjson.com/carts/1').then((res) => res.json());
};

export const getRevenue = () => {
  return fetch('https://dummyjson.com/carts').then((res) => res.json());
};

export const getInventory = () => {
  return fetch('https://dummyjson.com/products').then((res) => res.json());
};

export const logOut = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token
  localStorage.clear('userData');

  return fetch(`http://localhost:80/roshetta/api/users/logout`, {
    headers,
  }).then((res) => res.json());
};

export const viewActivation = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(
    'http://localhost:80/roshetta/api/admins/view_activation?filter=&type=doctor&status=0',
    { headers }
  ).then((res) => res.json());
};
