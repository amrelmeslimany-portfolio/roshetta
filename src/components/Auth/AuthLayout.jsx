import React from "react";
import { Space } from "antd";
import CopyRights from "../../components/Footer/CopyRights";
import images from "../../images";
import "./AuthLayout.scss";

const AuthLayout = ({ text, children }) => {
  return (
    <div className="intro-page">
      <div className="segment-box">
        <div className="text part">
          <Space
            direction="vertical"
            size="middle"
            align="center"
            className="wrap"
          >
            <img src={images.logo2} width={150} />
            <article className="header">
              <p className="intro-text">{text}</p>
            </article>
            {children}
          </Space>
          <CopyRights className="copyright" />
        </div>
        <div className="media part">
          <img src={images.authBG} className="media-img" alt="روشتة" />
        </div>
      </div>
    </div>
  );
};

export default AuthLayout;
