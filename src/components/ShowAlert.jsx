import React, { useEffect } from "react";
import { useGlobalContext } from "../context";
import { motion } from "framer-motion";
import { Alert } from "antd";

const ShowAlert = () => {
  const { alert, setAlert } = useGlobalContext();

  useEffect(() => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: "smooth",
    });
    const myTimeout = setTimeout(() => {
      setAlert({ msg: "", show: false, type: "", headMsg: "" });
    }, 2000);

    return () => {
      clearTimeout(myTimeout);
    };
  }, [alert.show]);
  return (
    <>
      {alert.show && (
        <motion.div
          initial={{ opacity: 0, scale: 0.5 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.5 }}
        >
          <Alert
            style={{
              marginBottom: 20,
            }}
            message={alert.headMsg}
            description={alert.msg}
            type={alert.type}
            showIcon
          />
        </motion.div>
      )}
    </>
  );
};

export default ShowAlert;
