import React from 'react';
import images from '../../../images';
import { GoVerified } from 'react-icons/go';
const PersonalData = () => {
  
  const userData = JSON.parse(localStorage.getItem('userData'));

  console.log(userData);

  return (
    <>
      <div className="bg-gray-200 flex flex-col justify-center items-center gap-4 h-screen ">
        <img src={userData.image} className='w-40 h-40 rounded-full' alt="doctor-info" />
        <div className="flex justify-between items-center">
          <h2 className="text-2xl text-roshetta">{userData.name}</h2>
          <GoVerified className="text-blue-600 mx-2" />
        </div>
        <h2 className="text-xl text-slate-500">{userData.ssd}</h2>
        <div className="flex justify-between items-center bg-white shadow-lg rounded-2xl gap-20 py-2 px-24">
          <div className="flex justify-center items-center flex-col gap-2">
            <p className="text-slate-400">الجنس</p>
            <p>ذكر</p>
          </div>
          <div className="flex justify-center items-center flex-col">
            <p className="text-slate-400">الجنس</p>
            <p>ذكر</p>
          </div>
          <div className="flex justify-center items-center flex-col">
            <p className="text-slate-400">الجنس</p>
            <p>ذكر</p>
          </div>
        </div>
      </div>
    </>
  );
};

export default PersonalData;
