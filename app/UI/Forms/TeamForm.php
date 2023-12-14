<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Utils\ArrayHash;
use Rally\Model\Entity\Team;
use Rally\Model\Entity\TeamMember;
use Rally\Model\Repository\MemberRepository;
use Rally\Model\Repository\RoleRepository;
use Rally\Model\Repository\TeamMemberRepository;
use Rally\Model\Repository\TeamRepository;
use Rally\UI\BaseForm;

class TeamForm extends BaseForm
{

	public function __construct(
		private ?Team $team,
		private readonly RoleRepository $roleRepository,
		private readonly TeamRepository $teamRepository,
		private readonly TeamMemberRepository $teamMemberRepository,
		private readonly MemberRepository $memberRepository,
	)
	{
		parent::__construct();
	}

	protected function init(BootstrapForm $form): void
	{
		// we need to translate most things manually due to parametrised messages, so it's easier to just ignore the automatic translation
		$form->setTranslator(null);

		$form->addText('name', $this->translator->translate('fields.name'))
			->setRequired($this->translator->translate('errors.name.required'));

		foreach ($this->roleRepository->getAll() as $role) {
			$members = $this->memberRepository->getPairsByRole($role);
			$name = $role->translation($this->translator->getTranslator()->getLocale())->name;
			$description = $this->translator->translate(
				$role->min === $role->max ? 'description.membersExact' : 'description.members',
				['min' => $role->min, 'max' => $role->max],
			);

			// for some reason, $form::MIN_LENGTH here does not work even though it's in documentation. Possibly because of the bootstrap renderer?
			$form->addMultiSelect('members_' . $role->type->value, $name, $members)
				->setOption('description', $description)
				->addRule($form::MIN_LENGTH, $this->translator->translate('errors.members.min', ['min' => $role->min, 'role' => $name]), $role->min)
				->addRule($form::MAX_LENGTH, $this->translator->translate('errors.members.max', ['max' => $role->max, 'role' => $name]), $role->max);
		}

		$form->addSubmit('submit', $this->translator->translate('fields.submit'));

		if ($this->team) {
			$defaults = ['name' => $this->team->name];

			/** @var TeamMember $teamMember */
			foreach ($this->team->members as $teamMember) {
				$key = 'members_' . $teamMember->role->type->value;
				if (!isset($defaults[$key])) {
					$defaults[$key] = [];
				}

				$defaults[$key][] = $teamMember->member->id;
			}

			$form->setDefaults($defaults);
		}
	}

	protected function validateForm(BootstrapForm $form, ArrayHash $values): void
	{
		$used = [];
		foreach ($this->roleRepository->getAll() as $role) {
			$key = 'members_' . $role->type->value;
			$name = $role->translation($this->translator->getTranslator()->getLocale())->name;
			$members = $this->memberRepository->getPairsByRole($role);
			$count = count($values->$key);

			if ($count < $role->min) {
				$form[$key]->addError($this->translator->translate('errors.members.min', ['min' => $role->min, 'role' => $name]));
			}

			if ($count > $role->max) {
				$form[$key]->addError($this->translator->translate('errors.members.max', ['max' => $role->max, 'role' => $name]));
			}

			foreach ($values->$key as $memberId) {
				if (!in_array($memberId, $used, true)) {
					$used[] = $memberId;
					continue;
				}

				$form[$key]->addError($this->translator->translate('errors.members.duplicate', ['member' => $members[$memberId], 'role' => $name]));
			}
		}
	}

	protected function formSuccess(BootstrapForm $form, ArrayHash $values): void
	{
		if (!$this->team) {
			$this->team = new Team;
		}

		$this->team->name = $values->name;
		foreach ($this->team->members->toArray() as $teamMember) {
			$this->teamMemberRepository->delete($teamMember, false);
		}
		$this->team->members->clear();

		foreach ($this->roleRepository->getAll() as $role) {
			$key = 'members_' . $role->type->value;
			foreach ($this->memberRepository->getByIds($values->$key) as $member) {
				$teamMember = new TeamMember;
				$teamMember->team = $this->team;
				$teamMember->member = $member;
				$teamMember->role = $role;
				$this->teamMemberRepository->save($teamMember, false);

				$this->team->members->add($teamMember);
			}
		}

		$this->teamRepository->save($this->team);
	}

	protected function getPrefix(): string
	{
		return 'team';
	}

}
