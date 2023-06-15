export const AUTH_STATES = {
  LOGIN: "LOGIN",
  LOGOUT: "LOGOUT",
};

const storageUser = localStorage.getItem("user");

export const INITIAL_AUTH_STATE = {
  user: storageUser ? JSON.parse(storageUser) : null,
};

export const authReducer = (state, action) => {
  switch (action.type) {
    case AUTH_STATES.LOGIN:
      localStorage.setItem("user", JSON.stringify(action.user));
      return { ...state, user: action.user };

    case AUTH_STATES.LOGOUT:
      localStorage.removeItem("user");
      return { ...state, user: null };
  }
};
