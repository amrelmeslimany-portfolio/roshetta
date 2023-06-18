import React from "react";

import { motion } from "framer-motion";

const AppWrapper = (Component, classNames) =>
  function HigherOrderComponent() {
    return (
      <motion.div
        transition={{ duration: 0 }}
        whileInView={{ opacity: [1, 1, 1] }}
        className="app__wrapper"
      >
        <Component className={`${classNames} `} />
      </motion.div>
    );
  };

export default AppWrapper;
