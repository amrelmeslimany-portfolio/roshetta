import React from 'react';
import images from '../../../images';
import { useGlobalContext } from '../../../context';

const DoctorHeader = () => {
  return (
    <div
      //  className="doctor__header "
      className="flex justify-between items-center border-b-2 p-1.5"
    >
      <div className="text-green-600 text-xl font-extrabold">
        30107231801999
      </div>
      <img className="object-contain w-40 h-10" src={images.logo2} alt="" />
    </div>
  );
};

export default DoctorHeader;
