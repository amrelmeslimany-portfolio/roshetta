import React, { useEffect, useState } from 'react';
import images from '../../../images';
import { GoVerified } from 'react-icons/go';
import { AppWrapper } from '../../../wrapper';
import { viewProfile } from '../API';
import { GiDoctorFace } from 'react-icons/gi';

let userData;
if (JSON.parse(localStorage.getItem('userData'))) {
  // setUserData(JSON.parse(localStorage.getItem('userData')));
  userData = JSON.parse(localStorage.getItem('userData'));
}
const PersonalData = () => {
  // const [userData, setUserData] = useState({});
  // console.log(JSON.parse(localStorage.getItem('userData')));

  useEffect(() => {
    viewProfile(userData.token).then((data) => {
      console.log(data);
    });
  }, []);
  return (
    <>
      <div className="px-40 py-2 bg-gray-200 h-screen ">
        <img
          // src={userData.image}
          src={images.doctorEditInfo}
          className="w-1/4 mx-auto block rounded-full"
          alt="doctor-info"
        />
        <div className="w-1/2 m-auto flex justify-around items-center">
          <h2 className="text-2xl text-roshetta">{userData.name}</h2>
          <GoVerified className="text-blue-600 mx-2" />
        </div>
        <h2 className="w-1/2 mx-auto text-xl text-slate-500">{userData.ssd}</h2>
        <div className="flex justify-between items-center bg-white shadow-lg rounded-2xl gap-20 py-2 px-24">
          <div className="flex justify-center items-center flex-col gap-2">
            <p className="text-slate-400">الجنس</p>
            <p>ذكر</p>
          </div>
          <div className="flex justify-center items-center flex-col">
            <p className="text-slate-400">تاريخ الميلاد</p>
            <p>تاريخ الميلاد</p>
          </div>
          <div className="flex justify-center items-center flex-col">
            <p className="text-slate-400">نوع الحساب</p>
            <p>{userData.type}</p>
          </div>
        </div>
        <div className="flex justify-start items-start flex-col">
          <div className="flex justify-start items-start">
            <GiDoctorFace />{' '}
            <div className="flex justify-start items-start flex-col">
              <p>التخصص</p>
              <p>باطنة</p>
            </div>
          </div>
          <div className="flex justify-start items-start">
            <GiDoctorFace />{' '}
            <div className="flex justify-start items-start flex-col">
              <p>التخصص</p>
              <p>باطنة</p>
            </div>
          </div>
          <div className="flex justify-start items-start">
            <GiDoctorFace />{' '}
            <div className="flex justify-start items-start flex-col">
              <p>التخصص</p>
              <p>باطنة</p>
            </div>
          </div>
          <div className="flex justify-start items-start">
            <GiDoctorFace />{' '}
            <div className="flex justify-start items-start flex-col">
              <p>التخصص</p>
              <p>باطنة</p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AppWrapper(PersonalData);
