import React from "react";
import ReactDOM from "react-dom/client";
import { AppProvider } from "./context";
import { ConfigProvider } from "antd";
import App from "./App";
import "./index.css";
import { DEFAULT_THEME } from "./constants/theme";
import AuthProvider from "./store/auth/context";

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <AppProvider>
      <AuthProvider>
        <ConfigProvider theme={DEFAULT_THEME} direction="rtl">
          <App />
        </ConfigProvider>
      </AuthProvider>
    </AppProvider>
  </React.StrictMode>
);
