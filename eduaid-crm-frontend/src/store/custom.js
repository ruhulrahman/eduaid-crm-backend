
// Page Options List
const pageOptions = [
  {
    value: 10,
    text: '10',
    text_bn: '১০'
  },
  {
    value: 20,
    text: '20',
    text_bn: '২০'
  },
  {
    value: 30,
    text: '30',
    text_bn: '৩০'
  },
  {
    value: 40,
    text: '40',
    text_bn: '৪০'
  },
  {
    value: 50,
    text: '50',
    text_bn: '৫০'
  },
  {
    value: 100,
    text: '100',
    text_bn: '১০০'
  }
]
const monthList = [
  {
    value: 1,
    text_en: 'January',
    text_bn: 'জানুয়ারী'
  },
  {
    value: 2,
    text_en: 'February',
    text_bn: 'ফেব্রুয়ারী'
  },
  {
    value: 3,
    text_en: 'March',
    text_bn: 'মার্চ'
  },
  {
    value: 4,
    text_en: 'April',
    text_bn: 'এপ্রিল'
  },
  {
    value: 5,
    text_en: 'May',
    text_bn: 'মে'
  },
  {
    value: 6,
    text_en: 'June',
    text_bn: 'জুন'
  },
  {
    value: 7,
    text_en: 'July',
    text_bn: 'জুলাই'
  },
  {
    value: 8,
    text_en: 'August',
    text_bn: 'আগস্ট'
  },
  {
    value: 9,
    text_en: 'September',
    text_bn: 'সেপ্টেম্বর'
  },
  {
    value: 10,
    text_en: 'October',
    text_bn: 'অক্টোবর'
  },
  {
    value: 11,
    text_en: 'November',
    text_bn: 'নভেম্বর'
  },
  {
    value: 12,
    text_en: 'December',
    text_bn: 'ডিসেম্বর'
  }
]
// Month List
const banglaMonthList = [
  {
    value: 1,
    text_en: 'Boishakh',
    text_bn: 'বৈশাখ'
  },
  {
    value: 2,
    text_en: 'Joishtho',
    text_bn: 'জ্যৈষ্ঠ'
  },
  {
    value: 3,
    text_en: 'Ashaarh',
    text_bn: 'আষাঢ়'
  },
  {
    value: 4,
    text_en: 'Srabon',
    text_bn: 'শ্রাবণ'
  },
  {
    value: 5,
    text_en: 'Badro',
    text_bn: 'ভাদ্র'
  },
  {
    value: 6,
    text_en: 'Ashsin',
    text_bn: 'আশ্বিন'
  },
  {
    value: 7,
    text_en: 'Kartik',
    text_bn: 'কার্তিক'
  },
  {
    value: 8,
    text_en: 'Ogrohaeon',
    text_bn: 'অগ্রহায়ণ'
  },
  {
    value: 9,
    text_en: 'Poush',
    text_bn: 'পৌষ'
  },
  {
    value: 10,
    text_en: 'Magh',
    text_bn: 'মাঘ'
  },
  {
    value: 11,
    text_en: 'Falgun',
    text_bn: 'ফাল্গুন'
  },
  {
    value: 12,
    text_en: 'Choitro',
    text_bn: 'চৈত্র'
  }
]

// price Variation
const priceVariationList = [
  {
    value: 1,
    text: 'Lowest',
    text_en: 'Lowest',
    text_bn: 'সর্বনিম্ন'
  },
  {
    value: 2,
    text: 'Highest',
    text_en: 'Highest',
    text_bn: 'সর্বোচ্চ'
  }
]
// day list
const fifteenDayList = [
  {
    value: 1,
    text: '1',
    text_en: '1',
    text_bn: '১'
  },
  {
    value: 2,
    text: '2',
    text_en: '2',
    text_bn: '২'
  },
  {
    value: 3,
    text: '3',
    text_en: '3',
    text_bn: '৩'
  },
  {
    value: 4,
    text: '4',
    text_en: '4',
    text_bn: '৪'
  },
  {
    value: 5,
    text: '5',
    text_en: '5',
    text_bn: '৫'
  },
  {
    value: 6,
    text: '6',
    text_en: '6',
    text_bn: '৬'
  },
  {
    value: 7,
    text: '7',
    text_en: '7',
    text_bn: '৭'
  },
  {
    value: 8,
    text: '8',
    text_en: '8',
    text_bn: '৮'
  },
  {
    value: 9,
    text: '9',
    text_en: '9',
    text_bn: '৯'
  },
  {
    value: 10,
    text: '10',
    text_en: '10',
    text_bn: '১০'
  },
  {
    value: 11,
    text: '11',
    text_en: '11',
    text_bn: '১১'
  },
  {
    value: 12,
    text: '12',
    text_en: '12',
    text_bn: '১২'
  },
  {
    value: 13,
    text: '13',
    text_en: '13',
    text_bn: '১৩'
  },
  {
    value: 14,
    text: '14',
    text_en: '14',
    text_bn: '১৪'
  },
  {
    value: 15,
    text: '15',
    text_en: '15',
    text_bn: '১৫'
  }
]
// week list
const weekList = [
  {
    value: 0,
    text_en: 'Sunday',
    text_bn: 'বরিবার'
  },
  {
    value: 1,
    text_en: 'Monday',
    text_bn: 'সোমবার'
  },
  {
    value: 2,
    text_en: 'Tuesday',
    text_bn: 'মঙ্গলবার'
  },
  {
    value: 3,
    text_en: 'Wednesday',
    text_bn: 'বুধবার'
  },
  {
    value: 4,
    text_en: 'Thursday',
    text_bn: 'বৃহস্পতিবার'
  },
  {
    value: 5,
    text_en: 'Friday',
    text_bn: 'শুক্রবার'
  },
  {
    value: 6,
    text_en: 'Saturday',
    text_bn: 'শনিবার'
  }
]

// Bangla month Days
const banglaMonthDays = [
  {
    value: 1,
    text: '1',
    text_en: '1',
    text_bn: '১'
  },
  {
    value: 2,
    text: '2',
    text_en: '2',
    text_bn: '২'
  },
  {
    value: 3,
    text: '3',
    text_en: '3',
    text_bn: '৩'
  },
  {
    value: 4,
    text: '4',
    text_en: '4',
    text_bn: '৪'
  },
  {
    value: 5,
    text: '5',
    text_en: '5',
    text_bn: '৫'
  },
  {
    value: 6,
    text: '6',
    text_en: '6',
    text_bn: '৬'
  },
  {
    value: 7,
    text: '7',
    text_en: '7',
    text_bn: '৭'
  },
  {
    value: 8,
    text: '8',
    text_en: '8',
    text_bn: '৮'
  },
  {
    value: 9,
    text: '9',
    text_en: '9',
    text_bn: '৯'
  },
  {
    value: 10,
    text: '10',
    text_en: '10',
    text_bn: '১০'
  },
  {
    value: 11,
    text: '11',
    text_en: '11',
    text_bn: '১১'
  },
  {
    value: 12,
    text: '12',
    text_en: '12',
    text_bn: '১২'
  },
  {
    value: 13,
    text: '13',
    text_en: '13',
    text_bn: '১৩'
  },
  {
    value: 14,
    text: '14',
    text_en: '14',
    text_bn: '১৪'
  },
  {
    value: 15,
    text: '15',
    text_en: '15',
    text_bn: '১৫'
  },
  {
    value: 16,
    text: '16',
    text_en: '16',
    text_bn: '১৬'
  },
  {
    value: 17,
    text: '17',
    text_en: '17',
    text_bn: '১৭'
  },
  {
    value: 18,
    text: '18',
    text_en: '18',
    text_bn: '১৮'
  },
  {
    value: 19,
    text: '19',
    text_en: '19',
    text_bn: '১৯'
  },
  {
    value: 20,
    text: '20',
    text_en: '20',
    text_bn: '২০'
  },
  {
    value: 21,
    text: '21',
    text_en: '21',
    text_bn: '২১'
  },
  {
    value: 22,
    text: '22',
    text_en: '22',
    text_bn: '২২'
  },
  {
    value: 23,
    text: '23',
    text_en: '23',
    text_bn: '২৩'
  },
  {
    value: 24,
    text: '24',
    text_en: '24',
    text_bn: '২৪'
  },
  {
    value: 25,
    text: '25',
    text_en: '25',
    text_bn: '২৫'
  },
  {
    value: 26,
    text: '26',
    text_en: '26',
    text_bn: '২৬'
  },
  {
    value: 27,
    text: '27',
    text_en: '27',
    text_bn: '২৭'
  },
  {
    value: 28,
    text: '28',
    text_en: '28',
    text_bn: '২৮'
  },
  {
    value: 29,
    text: '29',
    text_en: '29',
    text_bn: '২৯'
  },
  {
    value: 30,
    text: '30',
    text_en: '30',
    text_bn: '৩০'
  },
  {
    value: 31,
    text: '31',
    text_en: '31',
    text_bn: '৩১'
  }
]

const genderList = [
  {
    value: 1,
    text: 'Male',
    text_en: 'Male',
    text_bn: 'পুরুষ'
  },
  {
    value: 2,
    text: 'Female',
    text_en: 'Female',
    text_bn: 'মহিলা'
  },
  {
    value: 3,
    text: 'Others',
    text_en: 'Others',
    text_bn: 'অন্যান্য'
  }
]

function generateYearList () {
  const min = new Date().getFullYear()
  const oldMax = min - 35
  const newMax = min + 10
  const newYears = []
  const oldYears = []
  for (let i = oldMax; i < min; i++) {
    oldYears.push(i)
  }
  for (let j = min; j <= newMax; j++) {
    newYears.push(j)
  }
  const yearList = [...oldYears, ...newYears]
  return yearList
}

const yearList = generateYearList()

export default {
  pageOptions,
  monthList,
  priceVariationList,
  fifteenDayList,
  banglaMonthList,
  banglaMonthDays,
  genderList,
  yearList,
  weekList
}
