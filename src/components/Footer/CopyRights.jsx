import React from "react";
import { DEFAULT_THEME } from "../../constants/theme";
import { FaCopyright, FaRegCopyright } from "react-icons/fa";

const CopyRights = (props) => {
  const style = {
    fontSize: 12,
    textAlign: "center",
    padding: "10px",
    color: "#8c8c8c",
  };
  const colorprimary = { color: DEFAULT_THEME.token.colorPrimary };
  return (
    <p style={style} {...props}>
      برمجة فريق <strong style={colorprimary}>روشتة</strong>
    </p>
  );
};

export default CopyRights;
