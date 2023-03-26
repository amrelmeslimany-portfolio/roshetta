
const reducer = (state, action) => {
  if (action.type === 'SET_AUTH_USER') {
    return { ...state, auth: action.payload };
  }
  if (action.type === 'SET_ALERT_MESSAGE') {
    return { ...state, alert: action.payload };
  }
  
  throw new Error('No matching action type');
};

export default reducer;
