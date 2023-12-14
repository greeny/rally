<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Utils\ArrayHash;
use Rally\Model\Entity\Member;
use Rally\Model\Entity\Role;
use Rally\Model\Repository\MemberRepository;
use Rally\Model\Repository\RoleRepository;
use Rally\UI\BaseForm;

class MemberForm extends BaseForm
{

	public function __construct(
		private ?Member $member,
		private readonly MemberRepository $memberRepository,
		private readonly RoleRepository $roleRepository,
	)
	{
		parent::__construct();
	}

	protected function init(BootstrapForm $form): void
	{
		$form->addText('name', 'fields.name')
			->setRequired('errors.name.required');

		$form->addText('surname', 'fields.surname')
			->setRequired('errors.surname.required');

		$roles = $this->roleRepository->getTypePairs($this->translator->getTranslator()->getLocale());
		$form->addCheckboxList('roles', 'fields.roles', items: $roles)
			->setRequired('errors.roles.required')
			->setTranslator(null);

		$form->addSubmit('submit', 'fields.submit');

		if ($this->member) {
			$form->setDefaults([
				'name' => $this->member->name,
				'surname' => $this->member->surname,
				'roles' => $this->member->roles->map(function (Role $role) {
					return $role->id;
				})->toArray(),
			]);
		}
	}

	protected function validateForm(BootstrapForm $form, ArrayHash $values): void
	{
	}

	protected function formSuccess(BootstrapForm $form, ArrayHash $values): void
	{
		if (!$this->member) {
			$this->member = new Member;
		}

		$this->member->name = $values->name;
		$this->member->surname = $values->surname;
		$this->member->roles->clear();

		foreach ($this->roleRepository->getByIds($values->roles) as $role) {
			$this->member->roles->add($role);
		}

		$this->memberRepository->save($this->member);
	}

	protected function getPrefix(): string
	{
		return 'member';
	}

}
