<?php

namespace App\Containers\User\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\UI\WEB\Requests\RegisterUserRequestWEB;
use App\Containers\User\UI\WEB\Requests\RegisterPowerUserRequestWEB;
use App\Containers\User\UI\WEB\Requests\UpdateUserRequestWEB;
use App\Containers\User\UI\WEB\Requests\DeleteUserRequestWEB;
use App\Containers\User\UI\WEB\Requests\AttachPermissionToRoleRequestWEB;
use App\Containers\User\UI\WEB\Requests\RolePageAccessRequest;
use App\Containers\User\UI\WEB\Requests\AssignUserToRoleRequestWEB;
use App\Containers\User\UI\WEB\Requests\CreateRoleRequestWEB;
use App\Containers\User\UI\WEB\Requests\DeleteRoleRequestWEB;
use App\Containers\User\UI\WEB\Requests\PowerUpdateUserRequestWEB;
use App\Containers\User\UI\WEB\Requests\RevokeUserFromRoleRequestWEB;
use App\Containers\User\UI\WEB\Requests\UpdateUserAccessRequest;
use App\Containers\User\UI\WEB\Requests\UserProfileAccessRequest;
use App\Containers\User\UI\WEB\Requests\UserProfilePictureRequest;
use App\Containers\User\UI\WEB\Requests\UsersProfileAccessRequest;
use App\Ship\Transporters\DataTransporter;

/**
 * Class Controller
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Controller extends WebController
{

    /**
     * @return  \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sayWelcome()
    {   // user say welcome
        return view('user::user-welcome');
    }

    public function showRegisterPage()
    {   
        //Show Register Page
        return view('user::register-page');
    }

    public function showRegisterPowerPage()
    {   
        //Show Register Page
        $roles = Apiato::call('Authorization@GetAllRolesAction');

        return view('user::register-page-power', [
            'roles' => $roles,
        ]);
    }

    public function registerCheck(RegisterUserRequestWEB $request)
    {
        $user = Apiato::call('User@RegisterUserAction', [new DataTransporter($request)]);

        return redirect(route('home'))->with('status', 'Register successfully!');
    }

    /**
     * Register a power user.
     *
     * @param RegisterPowerUserRequestWEB $request the request object containing the user information
     * @return void
     */
    public function registerPowerCheck(RegisterPowerUserRequestWEB $request)
    {
        $user = Apiato::call('User@RegisterUserAction', [new DataTransporter($request)]);
        
        //Update the request with id
        $request->merge(['user_id' => $user->id]);

        $assign = Apiato::call('Authorization@AssignUserToRoleAction', [new DataTransporter($request)]);
        
        return redirect(route('home'))->with('status', 'Register power user successfully!');
    }

    public function showUpdatePageWithInfo(UpdateUserAccessRequest $request, $id)
    {   
        //Get User Information
        $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter($request->merge(['id' => $id]))]);

        $roles = Apiato::call('Authorization@GetAllRolesAction');

        $assigned = $user->roles;

        return view('user::update-page', [
                'user' => $user,
                'roles' => $roles,
                'id' => $id,
                'assigned' => $assigned,
        ]);
    }

    public function showDeletePage(DeleteUserRequestWEB $request, $id)
    {   
        $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter($request->merge(['id' => $id]))]);

        //Show Delete Page
        return view('user::delete-page', [
                'id' => $user->id,
                'name' => $user->name,
        ]);
    }

    public function showRolePage(RolePageAccessRequest $request, $action = null)
    {   

        $roles = Apiato::call('Authorization@GetAllRolesAction');
        $permissions = Apiato::call('Authorization@GetAllPermissionsAction');

        $assigned = null;
        $assigned = Apiato::call('Authorization@GetAllPermissionsAssignedToRoleAction');    

        return view('user::role-page', [
            'roles' => $roles->all(),
            'permissions' => $permissions->all(),
            'assigned' => $assigned,
            'action' => $action,
        ]);
    }

    public function changePermissionToRoleWEB(AttachPermissionToRoleRequestWEB $request, $action = null)
    {
        //dd($request->all());

        if($action == "attach") {
            Apiato::call('Authorization@AttachPermissionsToRoleAction', [new DataTransporter($request)]);
            $status = "Attached successfully!";
        }
        else if($action == "detach") {
            Apiato::call('Authorization@DetachPermissionsFromRoleAction', [new DataTransporter($request)]);
            $status = "Detached successfully!";
        }
        else {
            return redirect(back())->with('errors', 'Invalid action!');
        }

        
        $roles = Apiato::call('Authorization@GetAllRolesAction');
        $permissions = Apiato::call('Authorization@GetAllPermissionsAction');

        $assigned = null;
        $assigned = Apiato::call('Authorization@GetAllPermissionsAssignedToRoleAction');


        return view('user::role-page', [
            'roles' => $roles->all(),
            'permissions' => $permissions->all(),
            'assigned' => $assigned,
            'action' => $action,
            'status' => $status,
        ]);
    }

    public function updateUserWEB(UpdateUserRequestWEB $request, $id)
    {
        $user = Apiato::call('User@UpdateUserAction', [new DataTransporter($request->merge(['id' => $id]))]);

        return redirect()->back()->with('status', 'Update user successfully!');
    }

    public function powerUpdateUserWEB(PowerUpdateUserRequestWEB $request, $id)
    {

        $user = Apiato::call('User@UpdateUserAction', [new DataTransporter($request->merge(['id' => $id]))]);

        $request->merge(['user_id' => $user->id]);

        $sync_role = Apiato::call('Authorization@SyncUserRolesAction', [new DataTransporter($request->merge(['id' => $id]))]);

        return redirect()->back()->with('status', 'Update user successfully!');
    }

    public function deleteUserWEB(DeleteUserRequestWEB $request, $id)
    {

        Apiato::call('User@DeleteUserAction', [new DataTransporter($request->merge(['id' => $id]))]);

        return redirect(route('list-page'))->with('status', 'Delete user successfully!');
    }

    public function showUserProfile(UserProfileAccessRequest $request, $id) {

        $roles = Apiato::call('Authorization@GetAllRolesAction');


        $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter($request->merge(['id' => $id]))]);

        $assigned = $user->roles;

        return view('user::user-profile-page', [
            'roles' => $roles->all(),
            'user' => $user,
            'assigned' => $assigned,
        ]);
    }

    public function showUsersProfile(UsersProfileAccessRequest $request) {

        $roles = Apiato::call('Authorization@GetAllRolesAction');

        $users = Apiato::call('User@GetAllUsersAction', [$request->paginate]);

        return view('user::users-profile-page', [
            'roles' => $roles->all(),
            'users' => $users,
        ]);
    }

    public function assignUserToRole(AssignUserToRoleRequestWEB $request) {
        $user = Apiato::call('Authorization@AssignUserToRoleAction', [new DataTransporter($request)]);

        return redirect(route('user-role-page', ['action' => 'assign']))->with('status', 'Assigned role to user successfully!');
    }

    public function revokeUserFromRole(RevokeUserFromRoleRequestWEB $request) {
        $user = Apiato::call('Authorization@RevokeUserFromRoleAction', [new DataTransporter($request)]);

        return redirect(route('user-role-page', ['action' => 'revoke']))->with('status', 'Revoked role from user successfully!');
    }

    public function showCreateRolePage(RolePageAccessRequest $request) {
        $roles = Apiato::call('Authorization@GetAllRolesAction');

        return view('user::create-role-page', [
            'roles' => $roles->all(),
        ]);
    }

    public function createNewRole(CreateRoleRequestWEB $request) {
        
        $create = Apiato::call('Authorization@CreateRoleAction', [new DataTransporter($request)]);

        return redirect(route('create-role'))->with(['status' => 'Role created successfully!']);
    }

    public function deleteRole(DeleteRoleRequestWEB $request) {

        $delete_role = Apiato::call('Authorization@DeleteRoleAction', [new DataTransporter($request)]);

        return redirect(route('create-role'))->with('status','Role deleted successfully!');
    }

    public function searchResult() {
        $users = Apiato::call('User@ListAndSearchUsersAction');
        
        return;
    }

    public function profilePictureUpload(UserProfilePictureRequest $request, $id) {

        $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter($request->merge(['id' => $id]))]);

        if(!$user) return redirect(route('users-profile'))->with('error', 'Invalid User\' id');

        if($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();

            $filename = time(). '.' .$extension;
            $file->move('uploads/photos/', $filename);

            $user->social_avatar = $filename;
        }
        $user->save();

        return redirect(route('user-profile', ['id' => $request->id]))->with('status', 'Uploaded profile picture successfully');
    }
}
