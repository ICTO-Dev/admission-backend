<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Campus & Course Selection
            'campus' => 'nullable|string',
            'campusId' => 'nullable|integer',
            'courseApplied1st' => 'required|string',
            'course1Id' => 'nullable|integer',
            'courseApplied2nd' => 'required|string|different:courseApplied1st',
            'course2Id' => 'nullable|integer',
            'barangayId' => 'nullable',
            'barangay_id' => 'nullable',
            'studentType' => 'required|string',
            'schoolYear' => 'nullable|string',

            // Personal Information
            'lrn' => 'required|string|size:12',
            'lastName' => 'required|string|max:100',
            'firstName' => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'dateOfBirth' => 'required|date',
            'age' => 'required|integer|min:5|max:100',
            'sex' => 'required|string',
            'civilStatus' => 'required|string',
            'placeOfBirth' => 'required|string',
            'religion' => 'required|string',
            'nationality' => 'required|string',
            'presentAddress' => 'required|string',
            'permanentAddress' => 'required|string',
            'mobileNumber' => 'required|string',
            'emailAddress' => 'required|email',
            'photoUrl' => 'required|string',

            // Demographics
            'isIndigenous' => 'required|boolean',
            'indigenousGroup' => 'nullable|string',
            'isSoloParent' => 'required|boolean',

            // Family Profiles
            'fatherProfile' => 'required|array',
            'fatherProfile.fullName' => 'required|string',
            'fatherProfile.age' => 'nullable',
            'fatherProfile.birthplace' => 'nullable|string',
            'fatherProfile.educationalAttainment' => 'nullable|string',
            'fatherProfile.contactNumber' => 'nullable|string',
            'fatherProfile.occupation' => 'nullable|string',
            'fatherProfile.placeOfWork' => 'nullable|string',
            'fatherProfile.livingStatus' => 'required|string',
            'fatherProfile.livingWithFamily' => 'required|string',
            'fatherProfile.causeOfDeath' => 'nullable|string',

            'motherProfile' => 'required|array',
            'motherProfile.fullName' => 'required|string',
            'motherProfile.age' => 'nullable',
            'motherProfile.birthplace' => 'nullable|string',
            'motherProfile.educationalAttainment' => 'nullable|string',
            'motherProfile.contactNumber' => 'nullable|string',
            'motherProfile.occupation' => 'nullable|string',
            'motherProfile.placeOfWork' => 'nullable|string',
            'motherProfile.livingStatus' => 'required|string',
            'motherProfile.livingWithFamily' => 'required|string',
            'motherProfile.causeOfDeath' => 'nullable|string',

            'spouseProfile' => 'nullable|array',
            'spouseProfile.fullName' => 'nullable|string',
            'spouseProfile.age' => 'nullable',
            'spouseProfile.birthplace' => 'nullable|string',
            'spouseProfile.educationalAttainment' => 'nullable|string',
            'spouseProfile.contactNumber' => 'nullable|string',
            'spouseProfile.occupation' => 'nullable|string',
            'spouseProfile.placeOfWork' => 'nullable|string',
            'spouseProfile.livingStatus' => 'nullable|string',
            'spouseProfile.livingWithFamily' => 'nullable|string',
            'numberOfDependents' => 'nullable',

            'birthOrder' => 'required|string',
            'birthOrderOther' => 'nullable|string',
            'housingCondition' => 'required|string',
            'familyMonthlyIncome' => 'required|string',
            'languageSpoken' => 'required|string',

            // Siblings
            'siblings' => 'nullable|array',
            'siblings.*.id' => 'nullable',
            'siblings.*.name' => 'nullable|string',
            'siblings.*.age' => 'nullable',
            'siblings.*.sex' => 'nullable|string',
            'siblings.*.civilStatus' => 'nullable|string',
            'siblings.*.educationalAttainment' => 'nullable|string',

            // Education
            'elementary' => 'required|array',
            'elementary.schoolName' => 'required|string',
            'elementary.yearGraduated' => 'required|string',
            'elementary.address' => 'nullable|string',
            'elementary.awardsHonors' => 'nullable|string',

            'juniorHigh' => 'required|array',
            'juniorHigh.schoolName' => 'required|string',
            'juniorHigh.yearGraduated' => 'required|string',
            'juniorHigh.address' => 'nullable|string',
            'juniorHigh.awardsHonors' => 'nullable|string',

            'seniorHigh' => 'nullable|array',
            'seniorHigh.schoolName' => 'nullable|string',
            'seniorHigh.yearGraduated' => 'nullable|string',
            'seniorHigh.address' => 'nullable|string',
            'seniorHigh.trackStrand' => 'nullable|string',
            'seniorHigh.awardsHonors' => 'nullable|string',
            'seniorHigh.gwaG11' => 'nullable',
            'seniorHigh.gwaG12' => 'nullable',

            'college' => 'nullable|array',
            'college.schoolName' => 'nullable|string',
            'college.address' => 'nullable|string',
            'college.inclusiveYears' => 'nullable|string',
            'college.course' => 'nullable|string',
            'college.gwa' => 'nullable',
            'college.awardsHonors' => 'nullable|string',

            'firstGenerationStudent' => 'required|boolean',
            'familyCollegeGraduatesCount' => 'required',
            'futureOutlook' => 'required|string',

            // Health & Emergency
            'pwdStatus' => 'required|boolean',
            'pwdSpecs' => 'nullable|string',
            'hospitalizedStatus' => 'required|boolean',
            'hospitalizedReasons' => 'nullable|string',

            'emergencyContact' => 'required|array',
            'emergencyContact.name' => 'required|string',
            'emergencyContact.relation' => 'required|string',
            'emergencyContact.contactNo' => 'required|string',
            'emergencyContact.address' => 'required|string',
        ];
    }
}
