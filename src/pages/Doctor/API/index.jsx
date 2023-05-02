let token;
const getToken = () => {
  let tokenData;
  if (JSON.parse(localStorage.getItem('userData'))) {
    tokenData = JSON.parse(localStorage.getItem('userData'));
  }
  token = tokenData.token;
};
export const addClinic = (formData) => {
  getToken();
  return fetch('http://localhost:80/roshetta/api/doctors/add_clinic', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: formData,
  }).then((res) => res.json());
};

export const logOut = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token
  localStorage.clear('userData');

  return fetch(`http://localhost:80/roshetta/api/users/logout`, {
    headers,
  }).then((res) => res.json());
};
// هاااااااااااام جدا لاتنسى
// const getTaxAmount = (price, taxRate) => {
//   return Promise.resolve(Math.floor((price * taxRate) / 100));
// };
// useEffect(() => {
//   const headers = { Authorization: `Bearer ${userData.token}` };
//   axios
//     .get('http://localhost:80/roshetta/api/users/profile', { headers })
//     .then((response) => console.log(response.data));
// }, []);
export const viewProfile = () => {
  getToken();
  const headers = { Authorization: `Bearer ${token}` }; // auth header with bearer token

  return fetch(`http://localhost:80/roshetta/api/users/profile`, {
    headers,
  }).then((res) => res.json());
};
