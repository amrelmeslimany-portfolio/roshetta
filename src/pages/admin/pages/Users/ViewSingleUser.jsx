import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { viewUserDetails } from '../../API';
import images from '../../../../images';
import { GoVerified } from 'react-icons/go';

import {
  FaDemocrat,
  FaFileMedical,
  FaGasPump,
  FaLocationArrow,
  FaMailBulk,
  FaPhone,
} from 'react-icons/fa';
import { MyLoader } from '../../../../components';
const ViewSingleUser = () => {
  const { type, id } = useParams();
  const [user, setUser] = useState({});
  const [loading, setLoading] = useState(false);
  useEffect(() => {
    setLoading(true);
    viewUserDetails(type, id).then((res) => {
      console.log(res);
      setUser(res.Data);
      setLoading(false);
    });
  }, []);
  if (!user.name) {
    return (
      <>
        <MyLoader loading={loading} />
      </>
    );
  }
  if (user.name) {
    return (
      <>
        <div className="my-4 w-[80vw] bg-gray-100">
          <img
            // src={user.image}
            src={user.image}
            className="mx-auto block w-1/6 rounded-full"
            alt="doctor-info"
          />
          <div className="mx-auto flex items-center justify-center">
            <h2 className="text-2xl text-roshetta ">{user.name}</h2>
            <GoVerified className="mx-2 text-blue-600" />
          </div>
          <div className="mx-auto text-center">
            <h2 className="m-auto text-xl text-slate-400 text-slate-500">
              {user.ssd}
            </h2>
          </div>

          <div className="m-auto mt-2 flex w-1/2 items-center justify-evenly gap-20 rounded-2xl bg-white py-2 shadow-lg ">
            <div className="flex flex-col items-center justify-center gap-2">
              <p className="text-slate-400">الجنس</p>
              <p>{user.gender}</p>
            </div>
            <div className="flex flex-col items-center justify-center">
              <p className="text-slate-400">تاريخ الميلاد</p>
              <p>{user.birth_date}</p>
            </div>
            <div className="flex flex-col items-center justify-center">
              <p className="text-slate-400">نوع الحساب</p>
              <p>{user.type}</p>
            </div>
          </div>
          <div className="mx-auto mt-4 flex w-1/2 flex-col items-start justify-start gap-3">
            <div className="flex items-start justify-start ">
              <FaFileMedical className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">التخصص</p>
                <p className="font-bold text-slate-800">{user.specialist}</p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaMailBulk className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">البريد الإلكتروني</p>
                <p className="font-bold text-slate-800">{user.email}</p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaPhone className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">رقم التليفون</p>
                <p className="font-bold text-slate-800">{user.phone_number}</p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaLocationArrow className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">العنوان</p>
                <p className="font-bold text-slate-800">{user.governorate}</p>
              </div>
            </div>
          </div>
          <div className="mx-auto mt-4 flex w-1/2 items-center justify-around">
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">العيادات</p>
              <p className="font-bold text-slate-800">{user.number_clinic}</p>
            </div>
            <div className="flex flex-col items-start justify-start border-x-2 border-slate-300 px-20">
              <p className="text-slate-500">الروشتات</p>
              <p className="font-bold text-slate-800">
                {user.number_prescript}
              </p>
            </div>
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">المساعدين</p>
              <p className="font-bold text-slate-800">{user.number_appoint}</p>
            </div>
          </div>
          <div className="mx-auto flex items-center justify-center">
            {/* <button
            onClick={() => buttonMode('edit-info')}
            className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
            type="button"
          >
            تعديل الحساب
          </button> */}
          </div>
        </div>
      </>
    );
  }
};

export default ViewSingleUser;
