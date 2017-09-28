<?php

namespace App\Models\Auth\Traits\Attribute;

/**
 * Class UserAttribute.
 */
trait UserAttribute
{

	/**
	 * @return string
	 */
	public function getStatusLabelAttribute()
	{
		if ($this->isActive()) {
			return "<span class='badge badge-success'>".__('labels.general.active').'</span>';
		}

		return "<span class='badge badge-danger'>".__('labels.general.inactive').'</span>';
	}

	/**
	 * @return string
	 */
	public function getConfirmedLabelAttribute()
	{
		if ($this->isConfirmed()) {
			if ($this->id != 1 && $this->id != auth()->id()) {
				return '<a href="'.route('admin.auth.user.unconfirm',
						$this).'" data-toggle="tooltip" data-placement="top" title="'.__('buttons.backend.access.users.unconfirm').'" name="confirm_item"><span class="badge badge-success" style="cursor:pointer">'.__('labels.general.yes').'</span></a>';
			} else {
				return '<span class="badge badge-success">'.__('labels.general.yes').'</span>';
			}
		}

		return '<a href="'.route('admin.auth.user.confirm', $this).'" data-toggle="tooltip" data-placement="top" title="'.__('buttons.backend.access.users.confirm').'" name="confirm_item"><span class="badge badge-danger" style="cursor:pointer">'.__('labels.general.no').'</span></a>';
	}

    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return config('access.users.requires_approval') && $this->confirmed == 0;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name.' '.$this->last_name
            : $this->first_name;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * @return mixed
     */
    public function getPictureAttribute()
    {
        return $this->getPicture();
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPicture($size = false)
    {
        if (! $size) {
            $size = config('gravatar.default.size');
        }

        return gravatar()->get($this->email, ['size' => $size]);
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }

	/**
	 * @return string
	 */
	public function getEditButtonAttribute()
	{
		return '<a href="'.route('admin.auth.user.edit', $this).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"></i></a> ';
	}

	public function getActionButtonsAttribute()
	{
		if ($this->trashed()) {
			//return $this->restore_button.$this->delete_permanently_button;
		}

		return
			//$this->clear_session_button.
			//$this->login_as_button.
			//$this->show_button.
			$this->edit_button;
			//$this->change_password_button.
			//$this->status_button.
			//$this->confirmed_button.
			//$this->delete_button;
	}
}
