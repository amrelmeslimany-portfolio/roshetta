import { Button } from "antd";
import React from "react";

export const PrimaryButton = (props) => {
  return (
    <Button
      shape="round"
      className={`primary_bg ${props.className ?? ""}`}
      type="primary"
      {...props}
    >
      {props.children}
    </Button>
  );
};
