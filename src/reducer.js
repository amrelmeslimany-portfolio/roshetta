// Created By Muhammad El Hassan @2022 All rights reserved

const reducer = (state, action) => {
  if (action.type === 'SET_AUTH_USER') {
    return { ...state, auth: action.payload };
  }
  
  throw new Error('No matching action type');
};

export default reducer;
