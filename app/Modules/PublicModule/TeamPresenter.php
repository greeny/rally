<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

use Rally\Model\Entity\Team;
use Rally\Model\Repository\RoleRepository;
use Rally\Model\Repository\TeamRepository;
use Rally\UI\Forms\TeamForm;
use Rally\UI\Forms\TeamFormFactory;

final class TeamPresenter extends BaseSecuredPresenter
{

	private Team $team;

	public function __construct(
		private readonly RoleRepository $roleRepository,
		private readonly TeamRepository $teamRepository,
		private readonly TeamFormFactory $teamFormFactory,
	)
	{
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$this->template->add('roles', $this->roleRepository->getAll());
		$this->template->add('teams', $this->teamRepository->getAll());
	}

	public function actionEdit(int $id): void
	{
		$team = $this->teamRepository->getById($id);
		if (!$team) {
			$this->error('Team not found');
		}

		$this->team = $team;
	}

	protected function createComponentCreateTeamForm(): TeamForm
	{
		$form = $this->teamFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashSuccess('team.created');
			$this->redirect('default');
		};
		return $form;
	}

	protected function createComponentEditTeamForm(): TeamForm
	{
		$form = $this->teamFormFactory->create($this->team);
		$form->onSuccess[] = function () {
			$this->flashSuccess('team.edited');
			$this->redirect('default');
		};
		return $form;
	}

}
