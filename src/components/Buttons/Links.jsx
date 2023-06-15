import React from "react";
import { Link } from "react-router-dom";
import "./Links.scss";

const CustomLink = ({ to, children }) => {
  return (
    <Link to={to} className="text-link">
      {children}
    </Link>
  );
};

CustomLink.Outlined = ({ to, children, className }) => {
  return (
    <Link to={to} className={`outlined-link ${className ?? ""}`}>
      {children}
    </Link>
  );
};

CustomLink.Primary = ({ to, children, className }) => {
  return (
    <Link to={to} className={`primary-link ${className ?? ""}`}>
      {children}
    </Link>
  );
};

export default CustomLink;
