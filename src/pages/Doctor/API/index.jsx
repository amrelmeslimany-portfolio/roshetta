export const addClinic = (formData) => {
  return fetch('http://localhost:80/roshetta/api/doctors/add_clinic', {
    method: 'POST',
    // headers: {
    //   'content-type': 'multipart/form-data',
    // },
    body: formData,
  }).then((res) => res.json());
};
